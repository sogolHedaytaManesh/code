<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  Request  $request
	 * @return array
	 */
	public function toArray($request): array
	{
		dd($this->availabilities->price);
		return [
			'title'        => $this->title,
			'minimumPrice' => $this->whenLoaded('availabilities',$this->availabilities->price,0),
			'thumbnail'    => $this->thumbnail
		];
	}
}
