<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateAutosTable.
 */
class CreateAutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('status')->status('ACTIVE');
            $table->text('description');
            $table->string('color');
            $table->integer('year');
            $table->unsignedBigInteger('model_id');
            $table->foreign('model_id')->references('id')->on('auto_models');
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('auto_types');
            $table->unsignedBigInteger('motor_id');
            $table->foreign('motor_id')->references('id')->on('auto_motors');
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
        Schema::drop('autos');
    }
}
