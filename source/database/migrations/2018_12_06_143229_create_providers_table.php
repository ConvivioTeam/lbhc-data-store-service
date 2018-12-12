<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->boolean('published');
            $table->uuid('venue_id')->nullable();
            $table->uuid('contact_id')->nullable();
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
        Schema::dropIfExists('providers');
    }
}
