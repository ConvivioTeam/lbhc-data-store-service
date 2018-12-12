<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVenues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('service_id');
            $table->uuid('provider_id');
            $table->string('name');
            $table->string('address');
            $table->string('details');
            $table->datetime('created');
            $table->datetime('updated');
            $table->boolean('flagged');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venues');
    }
}
