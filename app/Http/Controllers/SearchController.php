<?php

namespace App\Http\Controllers;

use App\Http\Requests\Search\SearchRequest;
use App\Repositories\Availability\AvailabilityRepositoryInterface as AvailabilityRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
	/**
	 * @var AvailabilityRepository
	 */
	private AvailabilityRepository $availabilityRepository;

	/**
	 * @param AvailabilityRepository $availabilityRepository
	 */
	public function __construct(
		AvailabilityRepository $availabilityRepository,
	) {
		$this->availabilityRepository = $availabilityRepository;
	}

	/**
	 * @param SearchRequest $request
	 * @return ResourceCollection
	 */
	public function __invoke(SearchRequest $request): ResourceCollection
	{
		$parameters = $request->validated();
		$primaryProducts = $this->getPrimaryProducts($parameters);
		$thirdPartyProducts = $this->getThirdPartyProducts($parameters);
		return $this->availabilityRepository->toCollection($primaryProducts->merge($thirdPartyProducts));
	}

	/**
	 * @param $limitation
	 * @return LengthAwarePaginator|Collection
	 */
	private function getPrimaryProducts($limitation): LengthAwarePaginator|Collection
	{
		return $this->availabilityRepository->list(array_merge(['vendor' => 0], $limitation));
	}

	/**
	 * @param $limitation
	 * @return LengthAwarePaginator|Collection
	 */
	private function getThirdPartyProducts($limitation): LengthAwarePaginator|Collection
	{
		return $this->availabilityRepository->list(array_merge(['vendor' => 1], $limitation));
	}
}
