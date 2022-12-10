<?php

namespace App\Repositories\Integration;

use Illuminate\Support\Collection;

interface IntegrationRepositoryInterface
{
	public function fetchProducts(): Collection|null;

	public function fetchDetails(array $tourIDs): object|null;

	public function fetchPrices(string $date): Collection|null;

	public function fetchAvailability(array $tourIDs, array $dates): object|null;
}
