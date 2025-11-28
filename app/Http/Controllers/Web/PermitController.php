<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use App\Services\PermitService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PermitController extends Controller
{
    public function __construct(private PermitService $service) {}

    public function home()
    {
        return view('home');
    }

    public function documentation()
    {
        return view('documentation');
    }

    public function issuePermit()
    {
        return view('issue');
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag('issue', [
            'licence_plate' => ['required', 'string', 'regex:/^[A-Za-z0-9 ]{1,8}$/'],
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
        ]);

        $from = new \DateTime($validated['valid_from']);
        $to = new \DateTime($validated['valid_to']);

        // 24 hour rule
        if ($to->getTimestamp() - $from->getTimestamp() < 86400) {
            return back()
                ->withErrors(['valid_to' => '"Valid to" must be at least 24 hours after "Valid from".'], 'issue')
                ->withInput();
        }

        // Overlap rule
        $licencePlate = strtoupper(str_replace(' ', '', $validated['licence_plate']));
        $overlap = \App\Models\Permit::where('licence_plate', $licencePlate)
            ->where('valid_from', '<', $to)
            ->where('valid_to', '>', $from)
            ->exists();

        if ($overlap) {
            return back()
                ->withErrors(['valid_to' => 'Permit overlaps with an existing permit for this licence plate.'], 'issue')
                ->withInput();
        }

        $this->service->createPermit($validated);

        return redirect()->route('permits.issue')->with('success', 'Permit created successfully!');
    }

    public function check(Request $request)
    {
        $request->validateWithBag('check', [
            'licence_plate' => ['required', 'string', 'regex:/^[A-Za-z0-9 ]{1,8}$/'],
            'check_date' => 'nullable|date',
            'stay_start' => 'nullable|date',
            'stay_end' => 'nullable|date|after:stay_start'], [
                'stay_end.after' => '"End date time" must be after "Start date time".',
            ]
        );

        $options = [
            'include_related' => true,
            'include_all' => true,
        ];

        if ($request->filled('stay_start') && $request->filled('stay_end')) {
            $data = $this->service->checkDuration(
                $request->licence_plate,
                Carbon::parse($request->stay_start),
                Carbon::parse($request->stay_end),
                $options
            );
        } else {
            $checkTime = $request->filled('check_date')
                ? Carbon::parse($request->check_date)
                : null;

            $data = $this->service->checkPointInTime($request->licence_plate, $checkTime);
        }

        return view('home', array_merge($data, [
            'result' => $data['status'],
            'relatedPermits' => $data['related_permits'] ?? collect(),
            'allPermits' => $data['all_permits'] ?? collect(),
        ]));
    }

    public function index(Request $request)
    {
        $map = [
            'valid_from_desc' => ['valid_from', 'desc'],
            'valid_from_asc' => ['valid_from', 'asc'],
            'valid_to_desc' => ['valid_to', 'desc'],
            'valid_to_asc' => ['valid_to', 'asc'],
            'created_at_desc' => ['created_at', 'desc'],
            'created_at_asc' => ['created_at', 'asc'],
        ];

        $selected = $request->input('sort_option', 'valid_from_desc');
        [$sort, $direction] = $map[$selected] ?? $map['valid_from_desc'];

        $permits = Permit::orderBy($sort, $direction)
            ->paginate(20)
            ->appends(['sort_option' => $selected]);

        return view('permits', [
            'permits' => $permits,
            'sort' => $sort,
            'direction' => $direction,
            'selected' => $selected,
        ]);
    }
}
