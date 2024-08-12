<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ground_registries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fid');
            $table->bigInteger('fid_2');
            $table->string('street_number', 300)->nullable();
            $table->integer('zip_code');
            $table->string('colony', 200)->nullable();
            $table->string('ground_surface', 50)->nullable();
            $table->string('construction_surface', 50)->nullable();
            $table->integer('year_construction');
            $table->boolean('special_installation')->default(false);
            $table->string('ground_unit_value', 50);
            $table->string('ground_value', 50);
            $table->string('cve_vus', 100);
            $table->string('subsidy', 50);
            $table->unsignedBigInteger('municipality_id');
            $table->foreign('municipality_id')->references('id')->on('municipalities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ground_registries');
    }
};
