<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
	use HasFactory;

	/**
	 * @var string[]
	 */
	protected $fillable = ['name', 'description', 'thumbnail', 'vendor', 'foreign_id'];

	/**
	 * @var string[]
	 */
	protected $with = ['availabilities'];

	/**
	 * @return HasMany
	 */
	public function availabilities(): HasMany
	{
		return $this->hasMany(Availability::class);
	}
}
