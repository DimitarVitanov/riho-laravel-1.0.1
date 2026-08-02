<?php

namespace App\Observers\Est8ads;

use App\Events\Est8ads\PropertyMoveSubmitted;
use App\Events\Est8ads\PropertyRequestUpdated;
use App\Models\Est8ads\PropertyMove;

class PropertyMoveObserver
{
    private const MATERIAL = ['move_type', 'status', 'target_location', 'budget_min', 'budget_max', 'currency', 'requirements', 'submitted_at'];

    public function created(PropertyMove $move): void
    {
        if ($move->status === 'submitted' || $move->submitted_at) PropertyMoveSubmitted::dispatch($move);
    }

    public function updated(PropertyMove $move): void
    {
        if ($move->wasChanged(self::MATERIAL) && ($move->status === 'submitted' || $move->submitted_at)) PropertyRequestUpdated::dispatch($move);
    }
}
