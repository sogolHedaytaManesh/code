<?php

namespace App\Console\Commands;

use App\Services\IntegrationService;
use Illuminate\Console\Command;

class UpdateProducts extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'update:products';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'this command can update products data from  a third party service';

	/**
	 * @return void
	 */
	public function handle(): void
	{
		/** @var IntegrationService $integrationService */
		$weatherService = app(IntegrationService::class);
		$weatherService->fetchAndStoreProducts();
	}
}