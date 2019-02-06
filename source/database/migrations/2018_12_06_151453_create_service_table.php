<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->uuid('provider_id');
            $table->uuid('event_id')->nullable();
            $table->uuid('eligibility_id')->nullable();
            $table->uuid('costoption_id')->nullable();
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
        Schema::dropIfExists('service');
    }
}
