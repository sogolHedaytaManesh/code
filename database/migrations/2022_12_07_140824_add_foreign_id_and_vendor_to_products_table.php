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
		if (!Schema::hasColumns('products', ['foreign_id', 'vendor'])) {
			Schema::table('products', function (Blueprint $table) {
				$table->string('foreign_id')->after('id')->nullable();
				$table->tinyInteger('vendor')->after('foreign_id')->default(0)->comment(
					'0 => primary products, 1 => third part products'
				);
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
		if (Schema::hasColumns('products', ['foreign_id', 'vendor'])) {
			Schema::table('products', function (Blueprint $table) {
				$table->dropColumn('foreign_id');
				$table->dropColumn('vendor');
			});
		}
	}
};
