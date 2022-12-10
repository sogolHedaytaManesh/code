<?php

namespace App\Repositories\Integration;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class IntegrationRepository implements IntegrationRepositoryInterface
{
	/**
	 * @param array $tourIDs
	 * @return object|null
	 */
	public function fetchDetails(array $tourIDs): object|null
	{
		try {
			$responses = Http::pool(function (Pool $pool) use ($tourIDs) {
				$poolsList = [];
				foreach ($tourIDs as $tourID) {
					$poolsList[] = $pool->get(sprintf(env('THIRD_PARTY_FETCH_DETAILS'), $tourID));
				}

				return $poolsList;
			});

			foreach ($responses as $response) {
				return $response->object();
			}
		} catch (\Exception $e) {
			report($e);
		}

		return null;
	}

	/**
	 * @return object|null
	 */
	public function fetchProducts(): Collection|null
	{
		try {
			$response = HTTP::get(env('THIRD_PARTY_FETCH_PRODUCTS'));
			return collect($response->object());
		} catch (\Exception $e) {
			report($e);
			return null;
		}
	}

	/**
	 * @param string $date
	 * @return Collection|null
	 */
	public function fetchPrices(string $date): Collection|null
	{
		try {
			$response = HTTP::get(env('THIRD_PARTY_CHECK_PRICE'), [
				'travelDat' => $date
			]);

			return collect($response->object());
		} catch (\Exception $e) {
			report($e);
			return null;
		}
	}

	/**
	 * @param array $tourIDs
	 * @param array $dates
	 * @return object|null
	 */
	public function fetchAvailability(array $tourIDs, array $dates): object|null
	{
		try {
			$responses = Http::pool(function (Pool $pool) use ($tourIDs, $dates) {
				$poolsList = [];
				foreach ($tourIDs as $index => $tourID) {
					$poolsList[] = $pool->get(sprintf(env('THIRD_PARTY_CHECK_AVAILABILITY'), $tourID), [
						'travelDate' => $dates[$index]
					]);
				}

				return $poolsList;
			});

			$responseObject = new \stdClass();
			foreach ($responses as $index => $response) {
				$responseObject->{$index} = new \stdClass();
				$responseObject->{$index}->foreign_id = $tourIDs[$index];
				$responseObject->{$index}->date = $dates[$index];
				$responseObject->{$index}->available = $response->object()->available;
			}

			return $responseObject;
		} catch (\Exception $e) {
			report($e);
		}

		return null;
	}
}
