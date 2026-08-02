<?php

namespace App\Events\Est8ads;

use App\Models\Est8ads\PropertyMove;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PropertyRequestUpdated implements ShouldDispatchAfterCommit
{
    use Dispatchable, SerializesModels;
    public function __construct(public PropertyMove $propertyMove) {}
}
