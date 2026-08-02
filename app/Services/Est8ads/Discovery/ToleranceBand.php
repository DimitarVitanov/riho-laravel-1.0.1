<?php

namespace App\Services\Est8ads\Discovery;

/**
 * Graded tolerance scoring for a single numeric criterion.
 *
 * A buyer asking for 200 m² should still see 190 m² and 220 m² when a 10%
 * tolerance is allowed, but an exact hit must always outrank a near miss.
 * The band therefore returns three things: whether the value is an exact hit,
 * whether it is merely inside the tolerance window, and a score that decays
 * the further the value drifts from the target.
 */
class ToleranceBand
{
    /** Values within this percentage of the target count as exact. */
    public const EXACT_EPSILON_PERCENT = 2.0;

    /** Score retained at the very edge of the tolerance window. */
    private const EDGE_RETENTION = 0.6;

    /**
     * @param  float  $target     The requested value (m², budget, ...).
     * @param  float|null  $value The candidate value.
     * @param  float  $tolerance  Allowed deviation in percent.
     * @param  float  $maxPoints  Points awarded for an exact hit.
     * @param  bool   $upperBound True when exceeding the target is a defect (e.g. price over budget).
     *
     * @return array{status: string, points: float, deviation: float|null}
     *         status is one of exact, tolerance, outside, unknown.
     */
    public static function evaluate(
        ?float $target,
        ?float $value,
        float $tolerance,
        float $maxPoints,
        bool $upperBound = false,
    ): array {
        if (! $target) {
            return ['status' => 'exact', 'points' => $maxPoints, 'deviation' => null];
        }

        if ($value === null || $value <= 0.0) {
            return ['status' => 'unknown', 'points' => $maxPoints / 2, 'deviation' => null];
        }

        $deviation = round((($value - $target) / $target) * 100, 2);

        // A price at or under budget, or a size at or above the requested size,
        // satisfies the criterion outright.
        $satisfied = $upperBound ? $value <= $target : $value >= $target;

        if ($satisfied && abs($deviation) <= self::EXACT_EPSILON_PERCENT) {
            return ['status' => 'exact', 'points' => $maxPoints, 'deviation' => $deviation];
        }

        if ($satisfied) {
            // Better than asked for: still exact on the criterion itself.
            return ['status' => 'exact', 'points' => $maxPoints, 'deviation' => $deviation];
        }

        if (abs($deviation) <= $tolerance) {
            $ratio = $tolerance > 0 ? abs($deviation) / $tolerance : 0.0;
            $points = $maxPoints * (1 - $ratio * (1 - self::EDGE_RETENTION));

            return ['status' => 'tolerance', 'points' => round($points, 2), 'deviation' => $deviation];
        }

        return ['status' => 'outside', 'points' => 0.0, 'deviation' => $deviation];
    }
}
