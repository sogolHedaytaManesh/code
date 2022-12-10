<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Product;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		Product::query()->get()->each(function ($product) {
			Availability::factory()->times(2)->create(['product_id' => $product->id]);
		});
	}
}
