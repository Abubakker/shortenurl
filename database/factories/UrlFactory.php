<?php

namespace Database\Factories;

use App\Models\Url;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Url>
 */
class UrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Url::class;
    public function definition(): array
    {
        return [
            'original_url' => $this->faker->unique()->url,
            'short_code' => $this->faker->unique()->lexify('??????')
        ];
    }
}
