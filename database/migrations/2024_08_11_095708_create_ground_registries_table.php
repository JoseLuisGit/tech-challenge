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
            $table->float('ground_surface', 8, 8)->nullable();
            $table->float('construction_surface', 8, 8)->nullable();
            $table->integer('year_construction');
            $table->boolean('special_installation')->default(false);
            $table->float('ground_unit_value', 8, 8);
            $table->float('ground_value', 8, 8);
            $table->string('cve_vus', 100);
            $table->float('subsidy', 8, 8);
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
