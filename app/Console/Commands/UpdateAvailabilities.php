<?php

namespace App\Console\Commands;

use App\Services\IntegrationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateAvailabilities extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'update:availabilities';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'this command can update availability data from  a third party service';

	/**
	 * @return void
	 */
	public function handle(): void
	{
		/** @var IntegrationService $integrationService */
		$weatherService = app(IntegrationService::class);
		$date = Carbon::now()->format('Y-m-d');
		$weatherService->fetchAndStoreAvailabilities($date);
	}
}