<?php

namespace Database\Factories;

use Faker\Provider\HtmlLorem;
use Faker\Provider\Lorem;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
            return [
                'nombre' => fake()->name(),
                'precio' => rand(1,100),
                'stock' => rand(0,1000),
                'vendidos' => rand(0,5),
                'categoria' => Str::random(5),
                'tag' => Str::random(10),
                'descripcion'=> Lorem::text(),
                'informacion'=> Lorem::text(99),
                'valoracion'=> rand(1,5),
                'sku'=> Lorem::word(),
                'imagenes'=> fake()->name()
            ];
        
    }
}
