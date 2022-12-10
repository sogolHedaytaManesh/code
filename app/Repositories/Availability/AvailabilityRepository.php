<?php

namespace App\Repositories\Availability;

use App\Http\Resources\Availability\AvailabilityCollection;
use App\Http\Resources\Availability\AvailabilityResource;
use App\Models\Availability;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class AvailabilityRepository extends BaseRepository implements AvailabilityRepositoryInterface
{
	/**
	 * @return string
	 */
	public function getModelName(): string
	{
		return Availability::class;
	}

	/**
	 * @param Model $model
	 * @return JsonResource
	 */
	public function toResource(Model $model): JsonResource
	{
		return new AvailabilityResource($model);
	}

	/**
	 * @param Collection $collection
	 * @return ResourceCollection
	 */
	public function toCollection(Collection $collection): ResourceCollection
	{
		return new AvailabilityCollection($collection);
	}

	/**
	 * @param Builder $models
	 * @param array $queries
	 * @return Builder
	 */
	public function applyFilters(Builder $models, array $queries = []): Builder
	{
		if (Arr::has($queries, 'vendor') && $queries['vendor'] === 0) {
			$models = $models
				->whereHas('product', function (Builder $query) use ($queries) {
				$query->where('products.vendor', $queries['vendor']);
			})
				->whereDate('start_time', '>=', $queries['startDate'])
				->whereDate('end_time', '<=', $queries['endDate']);
		}

		if (Arr::has($queries, 'vendor') && $queries['vendor'] === 1) {
			$models = $models->whereHas('product', function (Builder $query) use ($queries) {
				$query->where('vendor', $queries['vendor']);
			})->whereBetween('start_time', [$queries['startDate'], $queries['endDate']]);
		}

		return parent::applyFilters($models);
	}
}
