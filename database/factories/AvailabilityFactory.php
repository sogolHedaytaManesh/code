<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class AvailabilityFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'price'      => $this->faker->numerify('###.##'),
			'start_time' => now()->toDateTimeString(),
			'end_time'   => now()->addWeeks(2)->toDateTimeString(),

		];
	}
}
