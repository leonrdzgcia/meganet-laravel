<?php

namespace Database\Factories;

use App\Models\Bundle;
use Illuminate\Database\Eloquent\Factories\Factory;

class BundleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bundle::class;

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
            'service_description'=>$this->faker->sentence,
            'price'=>$this->faker->randomNumber,
            'tax_include'=>$this->faker->boolean,
            'tax'=>$this->faker->numberBetween( 0, 3),
            'partners'=>$this->faker->numberBetween( 0, 3),
            'types_of_billing'=>$this->faker->randomElement(['Recurrente','Prepago']),
            'activation_free'=>$this->faker->randomDigitNotNull,
            'get_activation_free_when'=>$this->faker->randomElement(['En facturación del primer servicio','Al crear el servicio ']),
            'contract_duration'=>$this->faker->randomDigitNotNull,
            'automatic_renewal'=>$this->faker->boolean,
            'auto_reactivate'=>$this->faker->boolean,
            'cancellation_free'=>$this->faker->randomDigitNotNull,
            'prior_cancellation_free'=>$this->faker->randomDigitNotNull,
            'discount_period'=>$this->faker->randomDigitNotNull,
            'discount_value'=>$this->faker->randomDigitNotNull,
        ];
    }
}
