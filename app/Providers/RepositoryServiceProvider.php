<?php

namespace App\Providers;

use App\Repositories\Availability\AvailabilityRepository;
use App\Repositories\Availability\AvailabilityRepositoryInterface;
use App\Repositories\Integration\IntegrationRepository;
use App\Repositories\Integration\IntegrationRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register(): void
	{
		$this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
		$this->app->bind(AvailabilityRepositoryInterface::class, AvailabilityRepository::class);
		$this->app->bind(IntegrationRepositoryInterface::class, IntegrationRepository::class);
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		//
	}
}
