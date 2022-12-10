<?php

namespace App\Repositories\Product;

use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
	/**
	 * @return string
	 */
	public function getModelName(): string
	{
		return Product::class;
	}

	/**
	 * @param Model $model
	 * @return JsonResource
	 */
	public function toResource(Model $model): JsonResource
	{
		return new ProductResource($model);
	}

	/**
	 * @param Collection $collection
	 * @return ResourceCollection
	 */
	public function toCollection(Collection $collection): ResourceCollection
	{
		return new ProductCollection($collection);
	}

	/**
	 * @param Builder $models
	 * @param array $queries
	 * @return Builder
	 */
	public function applyFilters(Builder $models, array $queries = []): Builder
	{
		if (Arr::has($queries, 'vendor')) {
			$models->where('vendor', $queries['vendor']);
		}

		if (Arr::has($queries, 'foreign_id')) {
			$models->where('foreign_id', $queries['foreign_id']);
		}

		return parent::applyFilters($models);
	}

	/**
	 * @param object $parameters
	 * @return void
	 */
	public function store(object $parameters): void
	{
		try {
			Product::query()->updateOrCreate(
				[
					'foreign_id' => $parameters->id,
					'vendor'     => 1
				],
				[
					'name' => $parameters->title,
					'description' => $parameters->description,
					'thumbnail' => $parameters->photos[0]->url
				]
			);
		} catch (\Exception $e) {
			report($e);
		}
	}
}
