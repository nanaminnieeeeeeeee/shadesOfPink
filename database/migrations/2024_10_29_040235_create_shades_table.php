<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('shades', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color_code'); // Store hex code of the color
            $table->unsignedBigInteger('created_by'); // Store user who created the shade
            $table->timestamps();

            // Adding a foreign key to reference the users table
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shades');
    }
};