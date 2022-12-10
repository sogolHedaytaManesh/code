<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		if (!Schema::hasColumn('availabilities', 'is_available')) {
			Schema::table('availabilities', function (Blueprint $table) {
				$table->boolean('is_available')->after('end_time')->default(0);
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
	{
		if (Schema::hasColumn('availabilities',  'is_available')) {
			Schema::table('availabilities', function (Blueprint $table) {
				$table->dropColumn('is_available');
			});
		}
	}
};
