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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->unsignedDecimal('precio', $precision = 8, $scale = 2);
            $table->unsignedInteger('stock');
            $table->unsignedInteger('vendidos');
            $table->string('categoria', 50);
            $table->string('tag', 50);
            $table->text('descripcion', 201);
            $table->mediumText('informacion', 100);
            $table->tinyInteger('valoracion')->unsigned();
            $table->string('sku', 24);
            $table->string('imagenes', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
