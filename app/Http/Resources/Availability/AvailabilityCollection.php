<?php

namespace App\Http\Resources\Availability;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AvailabilityCollection extends ResourceCollection
{
	public $collects = AvailabilityResource::class;

}
