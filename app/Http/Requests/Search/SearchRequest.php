<?php

namespace App\Http\Requests\Search;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
	public function rules(): array
	{
		$startDate = new Carbon($this->startDate);
		return [
			'startDate' => [
				'required',
				'date_format:Y-m-d',
				'after_or_equal:now'
			],
			'endDate'   => [
				'required_with:startDate',
				'date_format:Y-m-d',
				'after_or_equal:startDate',
				'before_or_equal:' . $startDate->addWeeks(2)->format('Y-m-d')
			],
		];
	}

	/**
	 * @return void
	 */
	public function prepareForValidation(): void
	{
		if (!$this->has('startDate')) {
			$this->merge(['startDate' => Carbon::now()->format('Y-m-d')]);
			$this->merge(['endDate' => Carbon::now()->addWeeks(2)->format('Y-m-d')]);
		}
	}
}
