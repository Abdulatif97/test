<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateAutoMotorsTable.
 */
class CreateAutoMotorsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('auto_motors', function(Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->string('image')->nullable();
			$table->string('status')->default('ACTIVE');
			$table->string('tranz')->nullable();
			$table->string('accept')->nullable();
            $table->softDeletes('deleted_at', 0);
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('auto_motors');
	}
}
