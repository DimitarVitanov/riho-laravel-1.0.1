<?php

namespace App\Services\Est8ads\Discovery;

use App\Models\Est8ads\Property;
use App\Models\Est8ads\PropertyMove;
use Illuminate\Support\Str;

/**
 * Finds properties that OTHER EST8ADS members are already selling which match
 * what a member is looking to buy, within a +/- tolerance band on price and
 * size (15% by default).
 *
 * This is the internal counterpart to the web/AI discovery pipeline: instead of
 * scraping the open internet it matches a buyer against the shared EST8ADS +
 * Villa Bit listing pool. Villa Bit listings are mirrored into this pool (see
 * ChainDiscoveryDispatcher), so they are included automatically; a member's own
 * listings are always excluded so nobody is matched against themselves.
 */
class MemberMatchFinder
{
    /** Point weights per criterion; they sum to 100 for a perfect match. */
    private const PRICE_POINTS = 55.0;
    private const SIZE_POINTS = 30.0;
    private const TYPE_POINTS = 15.0;

    /**
     * Member matches for a whole move (uses its active "wanted" property).
     *
     * @return array<int, array<string, mixed>>
     */
    public function forMove(PropertyMove $move): array
    {
        $wanted = $move->properties()
            ->where('listing_type', 'wanted')
            ->where('status', 'active')
            ->latest('id')
            ->first();

        return $wanted ? $this->forWanted($wanted, $move) : [];
    }

    /**
     * Member matches for a single "wanted" property.
     *
     * @return array<int, array<string, mixed>>
     */
    public function forWanted(Property $wanted, ?PropertyMove $move = null): array
    {
        $move ??= $wanted->propertyMove;
        $ownProfileId = $move?->profile_id;

        $tolerance = ChainDiscoveryDispatcher::defaultTolerance();
        $budget = (float) ($wanted->asking_price ?: $move?->budget_max ?: 0) ?: null;
        $targetSize = $wanted->floor_area !== null ? (float) $wanted->floor_area : null;
        $city = Str::lower(trim((string) $wanted->city));
        $type = Str::lower(trim((string) $wanted->property_type));

        $candidates = Property::query()
            ->where('listing_type', 'sell')
            ->where('status', 'active')
            ->when($city !== '', fn ($query) => $query->whereRaw('LOWER(city) LIKE ?', ['%' . $city . '%']))
            ->whereHas('propertyMove', function ($query) use ($ownProfileId) {
                $query->whereIn('status', ['active', 'submitted']);
                if ($ownProfileId) {
                    $query->where('profile_id', '!=', $ownProfileId);
                }
            })
            ->with('propertyMove.profile')
            ->limit(200)
            ->get();

        $matches = [];

        foreach ($candidates as $sell) {
            $sellPrice = (float) $sell->asking_price ?: null;
            $sellSize = $sell->floor_area !== null ? (float) $sell->floor_area : null;

            // Price is the hard criterion: a sell price more than the tolerance
            // over budget is not a match (upper bound — over budget is a defect).
            $priceEval = ToleranceBand::evaluate($budget, $sellPrice, $tolerance, self::PRICE_POINTS, true);
            if ($priceEval['status'] === 'outside') {
                continue;
            }

            // Size is a lower bound: a smaller-than-wanted home is a defect.
            $sizeEval = ToleranceBand::evaluate($targetSize, $sellSize, $tolerance, self::SIZE_POINTS, false);
            if ($sizeEval['status'] === 'outside') {
                continue;
            }

            $typeMatches = $type === '' || Str::lower(trim((string) $sell->property_type)) === $type;
            $typePoints = $typeMatches ? self::TYPE_POINTS : 0.0;

            $score = round($priceEval['points'] + $sizeEval['points'] + $typePoints, 1);
            $exact = $priceEval['status'] === 'exact' && $sizeEval['status'] === 'exact' && $typeMatches;

            $matches[$sell->id] = [
                'id' => 'MEMBER-' . $sell->id,
                'kind' => $exact ? 'exact' : 'member',
                'title' => $sell->title ?: 'Member property',
                'city' => $sell->city,
                'size' => $sellSize,
                'price' => $sellPrice,
                'currency' => $sell->currency ?: 'EUR',
                'score' => $score,
                'owner' => $this->ownerName($sell),
                'url' => data_get($sell->metadata, 'listing_url'),
                'explanation' => $this->explain($priceEval, $sizeEval, $typeMatches, $tolerance),
                'sizeNote' => $this->deviationNote($sizeEval),
                'priceNote' => $this->deviationNote($priceEval),
            ];
        }

        $matches = array_values($matches);
        usort($matches, fn ($a, $b) => $b['score'] <=> $a['score']);

        return $matches;
    }

    private function ownerName(Property $sell): string
    {
        $profile = $sell->propertyMove?->profile;

        return $profile?->company_name
            ?: trim(($profile?->first_name ?? '') . ' ' . ($profile?->last_name ?? ''))
            ?: 'EST8ADS member';
    }

    /**
     * @param  array{status: string, points: float, deviation: float|null}  $priceEval
     * @param  array{status: string, points: float, deviation: float|null}  $sizeEval
     */
    private function explain(array $priceEval, array $sizeEval, bool $typeMatches, float $tolerance): string
    {
        $parts = ['Another EST8ADS member is selling this property.'];

        $parts[] = $priceEval['status'] === 'tolerance' && $priceEval['deviation'] !== null
            ? sprintf('Price is %+.1f%% vs your budget — inside your %s%% range.', $priceEval['deviation'], rtrim(rtrim(number_format($tolerance, 1), '0'), '.'))
            : 'Price is within your budget.';

        if ($sizeEval['status'] === 'tolerance' && $sizeEval['deviation'] !== null) {
            $parts[] = sprintf('Size is %+.1f%% vs requested.', $sizeEval['deviation']);
        }

        if (! $typeMatches) {
            $parts[] = 'Different property type.';
        }

        return implode(' ', $parts);
    }

    /**
     * @param  array{status: string, points: float, deviation: float|null}  $eval
     */
    private function deviationNote(array $eval): ?string
    {
        if ($eval['status'] !== 'tolerance' || $eval['deviation'] === null) {
            return null;
        }

        return sprintf('%+.1f%% vs requested', $eval['deviation']);
    }
}
