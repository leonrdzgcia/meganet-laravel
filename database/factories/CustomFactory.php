<?php

namespace Database\Factories;

use App\Models\Custom;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Custom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'=>$this->faker->sentence,
            'enable'=>$this->faker->boolean,
            'service_name'=>$this->faker->sentence,
            'price'=>$this->faker->randomNumber,
           // 'partners'=>$this->faker->numberBetween( 0, 3),
            'tax_include'=>$this->faker->boolean,
            'tax'=>$this->faker->numberBetween( 0, 3),
            'types_of_billing'=>$this->faker->randomElement(['Recurrente', 'Prepago' ]),
            'prepaid_period'=>$this->faker->randomElement(['Mensual', 'Diario'])
        ];
    }
}
