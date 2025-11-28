<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PermitService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PermitController extends Controller
{
    public function __construct(private PermitService $service) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'licence_plate' => ['required', 'string', 'regex:/^[A-Za-z0-9 ]{1,8}$/'],
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
        ]);

        // Custom overlap validation
        $from = new \DateTime($validated['valid_from']);
        $to = new \DateTime($validated['valid_to']);

        $overlap = \App\Models\Permit::where('licence_plate', strtoupper(str_replace(' ', '', $validated['licence_plate'])))
            ->where('valid_from', '<', $to)
            ->where('valid_to', '>', $from)
            ->exists();

        if ($overlap) {
            return response()->json([
                'message' => 'Permit overlaps with an existing permit for this licence plate.'
            ], 422);
        }

        $permit = $this->service->createPermit($validated);

        return response()->json($permit, 201);
    }

    public function check(Request $request)
    {
        $request->validate([
            'licence_plate' => ['required', 'string', 'regex:/^[A-Za-z0-9 ]{1,8}$/'],
            'check_date' => 'nullable|date',
            'stay_start' => 'nullable|date',
            'stay_end' => 'nullable|date|after:stay_start',
        ]);

        $options = [
            'include_related' => $request->boolean('include_related', false),
            'include_all' => $request->boolean('include_all', false),
        ];

        if ($request->filled('stay_start') && $request->filled('stay_end')) {
            $payload = $this->service->checkDuration(
                $request->licence_plate,
                Carbon::parse($request->stay_start),
                Carbon::parse($request->stay_end),
                $options
            );
        } else {
            $checkTime = $request->filled('check_date')
                ? Carbon::parse($request->check_date)
                : null;

            $payload = $this->service->checkPointInTime(
                $request->licence_plate,
                $checkTime,
                $options
            );
        }

        return response()->json($payload);
    }
}
