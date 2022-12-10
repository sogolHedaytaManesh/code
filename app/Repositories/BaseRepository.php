<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BaseRepository implements BaseRepositoryInterface
{
	/**
	 * @return string
	 */
	public function getModelName(): string
	{
		return 'NoModel';
	}

	public function getModel(): Model
	{
		return app($this->getModelName());
	}

	/**
	 * @param Model $model
	 * @return JsonResource
	 */
	public function toResource(Model $model): JsonResource
	{
		return new JsonResource($model);
	}

	/**
	 * @param Collection $collection
	 * @return ResourceCollection
	 */
	public function toCollection(Collection $collection): ResourceCollection
	{
		return new ResourceCollection($collection);
	}

	/**
	 * @param array $queries
	 * @param array $relations
	 * @param array $select
	 * @return LengthAwarePaginator|Collection
	 */
	public function list(
		array $queries = [],
		array $relations = [],
		array $select = ['*']
	): LengthAwarePaginator|Collection {
		$models = $this->getModel()->query()->select($select)->with($relations);
		$models = $this->applyFilters($models, $queries);

		return $models->get();
	}

	/**
	 * @param Model $model
	 * @return Model
	 */
	public function show(Model $model): Model
	{
		return $model;
	}

	/**
	 * @param int $id
	 * @return Model|null
	 */
	public function find(int $id): ?Model
	{
		return $this->getModel()->query()->find($id);
	}

	/**
	 * @param array $parameters
	 * @return Model
	 */
	public function create(array $parameters): Model
	{
		/** @var Model $model */
		$model = $this->getModel()->query()
			->create($parameters);

		return $model;
	}

	/**
	 * @param Model $model
	 * @param array $parameters
	 * @return Model
	 */
	public function update(Model $model, array $parameters): Model
	{
		$model->update($parameters);

		return $model->refresh();
	}

	/**
	 * @param Model $model
	 * @return bool
	 */
	public function destroy(Model $model): bool
	{
		return $model->delete();
	}

	/**
	 * @param Builder $models
	 * @param array $queries
	 * @return Builder
	 */
	public function applyFilters(Builder $models, array $queries = []): Builder
	{
		return $models;
	}
}
