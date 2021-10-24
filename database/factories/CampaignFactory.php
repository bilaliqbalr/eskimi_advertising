<?php

namespace Database\Factories;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CampaignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Campaign::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'date_from' => now(),
            'date_to' => $this->faker->dateTimeBetween('+1 day', '+3 months'),
            'total_budget' => $this->faker->randomFloat(2, 100, 1000),
            'daily_budget' => $this->faker->randomFloat(2, 1, 100),
            'image' => $this->faker->image('public/images'),
        ];
    }
}
