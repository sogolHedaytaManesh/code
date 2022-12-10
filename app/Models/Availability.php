<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
	use HasFactory;

	/**
	 * @var string[]
	 */
	protected $fillable = ['product_id','price', 'start_time', 'end_time', 'is_available'];

	/**
	 * @return BelongsTo
	 */
	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}
}
