<?php

namespace App\Services;

use App\Models\Permit;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PermitService
{
    public function createPermit(array $data): Permit
    {
        // remove all whitespaces from licence plate
        $data['licence_plate'] = $this->formatPlate($data['licence_plate']);

        return Permit::create($data);
    }

    public function checkPointInTime(string $licencePlate, ?Carbon $checkTime = null, array $options = []): array
    {
        /**
         * $options:
         *   'include_related' => bool
         *   'include_all'     => bool
         *
         * coverage is always null for point-in-time checks
         */
        $opts = array_merge([
            'include_related' => false,
            'include_all' => false,
        ], $options);

        $formattedPlate = $this->formatPlate($licencePlate);
        $checkTime = $checkTime ?? now();

        $permit = Permit::where('licence_plate', $formattedPlate)
            ->where('valid_from', '<=', $checkTime)
            ->where('valid_to', '>=', $checkTime)
            ->first();

        return [
            'licence_plate' => $formattedPlate,
            'status' => $permit ? 'covered' : 'not_covered',
            'related_permits' => $opts['include_related'] ? ($permit ? collect([$permit]) : collect()) : null,
            'all_permits' => $opts['include_all'] ? $this->getAllPermits($formattedPlate) : null,
            'coverage' => null,
            'check_time' => $checkTime,
        ];
    }

    public function checkDuration(string $licencePlate, Carbon $stayStart, Carbon $stayEnd, array $options = []): array
    {
        /**
         * $options:
         *   'include_related' => bool
         *   'include_all'     => bool
         *
         * coverage is always returned for duration checks (not optional)
         */
        $opts = array_merge([
            'include_related' => false,
            'include_all' => false,
        ], $options);

        $formattedPlate = $this->formatPlate($licencePlate);

        $permits = $this->getPermitsForWindow($formattedPlate, $stayStart, $stayEnd);

        if ($permits->isEmpty()) {
            $status = 'not_covered';
            $coverage = [
                'covered_periods' => [],
                'uncovered_periods' => [[
                    'start' => $stayStart->toDateTimeString(),
                    'end' => $stayEnd->toDateTimeString(),
                ]],
            ];
        } else {
            $fullyCovered = $permits->contains(fn ($permit) => $permit->valid_from <= $stayStart && $permit->valid_to >= $stayEnd);
            $status = $fullyCovered ? 'covered' : 'partially_covered';
            $coverage = $this->calculateCoverage($permits, $stayStart, $stayEnd);
        }

        return [
            'licence_plate' => $formattedPlate,
            'status' => $status,
            'related_permits' => $opts['include_related'] ? $permits : null,
            'all_permits' => $opts['include_all'] ? $this->getAllPermits($formattedPlate) : null,
            'coverage' => $coverage,
            'stay_start' => $stayStart,
            'stay_end' => $stayEnd,
        ];
    }

    public function getAllPermits(string $formattedPlate): Collection
    {
        return Permit::where('licence_plate', $formattedPlate)
            ->orderByDesc('valid_from')
            ->get();
    }

    // helpers
    private function getPermitsForWindow(string $formattedPlate, Carbon $stayStart, Carbon $stayEnd): Collection
    {
        return Permit::where('licence_plate', $formattedPlate)
            ->where('valid_from', '<', $stayEnd)
            ->where('valid_to', '>', $stayStart)
            ->orderByDesc('valid_from')
            ->get();
    }

    private function calculateCoverage(Collection $permits, Carbon $stayStart, Carbon $stayEnd): array
    {
        $coverage = [
            'covered_periods' => [],
            'uncovered_periods' => [],
        ];

        $current = $stayEnd->copy();

        foreach ($permits as $permit) {
            $permitStart = $permit->valid_from;
            $permitEnd = $permit->valid_to;

            // If there's a gap between current and this permit's end, add uncovered period
            if ($permitEnd < $current) {
                $gapStart = max($permitEnd, $stayStart);
                if ($gapStart < $current) {
                    $coverage['uncovered_periods'][] = [
                        'start' => $gapStart->toDateTimeString(),
                        'end' => $current->toDateTimeString(),
                    ];
                }
                $current = $permitEnd->copy();
            }

            // Add covered period if it overlaps with the window
            $coveredStart = max($permitStart, $stayStart);
            $coveredEnd = min($permitEnd, $current);

            if ($coveredStart < $coveredEnd) {
                $coverage['covered_periods'][] = [
                    'start' => $coveredStart->toDateTimeString(),
                    'end' => $coveredEnd->toDateTimeString(),
                    'permit_id' => $permit->id,
                ];
                $current = $coveredStart->copy();
            }

            // If we've reached or passed the start, stop
            if ($current <= $stayStart) {
                break;
            }
        }

        // If there's still time left at the start, add final uncovered period
        if ($current > $stayStart) {
            $coverage['uncovered_periods'][] = [
                'start' => $stayStart->toDateTimeString(),
                'end' => $current->toDateTimeString(),
            ];
        }

        // Reverse arrays to chronological order
        $coverage['covered_periods'] = array_reverse($coverage['covered_periods']);
        $coverage['uncovered_periods'] = array_reverse($coverage['uncovered_periods']);

        return $coverage;
    }

    public function formatPlate(string $plate): string
    {
        return strtoupper(str_replace(' ', '', $plate));
    }
}
