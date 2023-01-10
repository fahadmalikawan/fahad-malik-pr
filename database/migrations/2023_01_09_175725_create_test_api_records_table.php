<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_api_records', function (Blueprint $table) {
            $table->id();
            $table->string('api', 255);
            $table->text('description');
            $table->string('auth', 50);
            $table->boolean('https');
            $table->string('cors', 10);
            $table->string('link', 255);
            $table->string('category', 50);
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
        Schema::dropIfExists('test_api_records');
    }
};
