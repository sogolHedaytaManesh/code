<?php

namespace App\Services;

use App\Repositories\Availability\AvailabilityRepositoryInterface as AvailabilityRepository;
use App\Repositories\Integration\IntegrationRepositoryInterface as IntegrationRepository;
use App\Repositories\Product\ProductRepositoryInterface as ProductRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class IntegrationService
{
	/**
	 * @var IntegrationRepository
	 */
	private IntegrationRepository $integrationRepository;

	/**
	 * @var ProductRepository
	 */
	private ProductRepository $productRepository;

	/**
	 * @var AvailabilityRepository
	 */
	private AvailabilityRepository $availabilityRepository;

	/**
	 * @param IntegrationRepository $integrationRepository
	 * @param ProductRepository $productRepository
	 * @param AvailabilityRepository $availabilityRepository
	 */
	public function __construct(
		IntegrationRepository $integrationRepository,
		ProductRepository $productRepository,
		AvailabilityRepository $availabilityRepository
	) {
		$this->integrationRepository = $integrationRepository;
		$this->productRepository = $productRepository;
		$this->availabilityRepository = $availabilityRepository;
	}

	/**
	 * @param Collection $products
	 * @return void
	 */
	public function fetchProductsByForeignID(Collection $products): void
	{
		$products->chunk(env('THIRD_PARTY_FETCHING_COUNT'))->each(function ($product) {
			$productDetail = $this->integrationRepository->fetchDetails($product->pluck('id')->toArray());
			if ($productDetail) {
				$this->productRepository->store($productDetail);
			}
		});
	}

	/**
	 * @param Collection $prices
	 * @param string $date
	 * @return void
	 */
	public function storePricesAndAvailabilities(Collection $prices, string $date): void
	{
		// we can fetch products from Redis to increase the race of fetching data
		$products = $this->productRepository->list(['vendor' => 1], ['availabilities'], ['id', 'foreign_id']);
		$prices->each(function ($price) use ($date, $products) {
			if ($product = $products->where('foreign_id', $price->tourId)->first()) {
				if ($selectedAvailability = $product->availabilities()->whereDate('start_time', $date)->first()) {
					$product->availabilities()->whereDate('start_time', $date)->update(['price' => $price->price]);
				} else {
					$selectedAvailability = $this->availabilityRepository->create([
						'product_id' => $product->id,
						'price'      => $price->price,
						'start_time' => $date,
						'end_time'   => $date,

					]);
				}

				$productAvailabilities = $this->integrationRepository->fetchAvailability([$product->foreign_id], [$date]
				);
				$product->refresh();
				foreach ($productAvailabilities as $productAvailability) {
					if ($selectedAvailability->is_available != $productAvailability->available) {
						$product->availabilities()->whereDate('start_time', $date)->update(
							['is_available' => $productAvailability->available]
						);
					}
				}
			}
		});
	}

	/**
	 * @return void
	 */
	public function fetchAndStoreProducts(): void
	{
		// we can store data to chunk data for Store operation
		if ($products = $this->integrationRepository->fetchProducts()) {
			$this->fetchProductsByForeignID($products);
		}
	}

	/**
	 * @param string $date
	 * @return void
	 */
	public function fetchAndStorePricesAndAvailabilities(string $date): void
	{
		$start = $date;
		$end = Carbon::now()->addWeeks(2)->format('Y-m-d');
		while ($start <= $end) {
			if ($prices = $this->integrationRepository->fetchPrices($start)) {
				$this->storePricesAndAvailabilities($prices, $start);
				$start = new Carbon($start);
				$start = $start->addDay()->format('Y-m-d');
			}
		}
	}

	/**
	 * @param $date
	 * @return void
	 */
	public function fetchAndStoreAvailabilities($date): void
	{
		// we can fetch products from Redis to increase the race of fetching data
		if ($products = $this->productRepository->list(['vendor' => 1], ['availabilities'], ['id', 'foreign_id'])) {
			$products->chunk(env('THIRD_PARTY_FETCHING_COUNT'))->each(function ($product) use ($date) {
				$start = $date;
				$end = Carbon::now()->addWeeks(2)->format('Y-m-d');
				while ($start <= $end) {
					$productAvailabilities = $this->integrationRepository->fetchAvailability(
						$product->pluck('foreign_id')->toArray(),
						array_fill(0, getenv('THIRD_PARTY_FETCHING_COUNT'), $date)
					);
					if ($productAvailabilities) {
						foreach ($productAvailabilities as $productAvailability) {
							$product->where('foreign_id', $productAvailability->foreign_id)->first()
								->availabilities()->whereDate('start_time', $start)->update(
									['is_available' => $productAvailability->available]
								);
						}

						$start = new Carbon($start);
						$start = $start->addDay()->format('Y-m-d');
					}
				}
			});
		}
	}
}

