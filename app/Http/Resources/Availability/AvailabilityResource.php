<?php

namespace App\Http\Resources\Availability;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  Request  $request
	 * @return array
	 */
	public function toArray($request): array
	{
		return [
			'title'        => $this->whenLoaded('product', $this->product->name, null),
			'minimumPrice' => $this->price,
			'thumbnail'    => $this->whenLoaded('product', $this->product->thumbnail, null),
		];
	}
}
