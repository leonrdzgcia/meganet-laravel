<?php

namespace Database\Factories;

use App\Models\Internet;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Internet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
           'title'=>'Basico_20MB',
            'enable'=>true,
            'service_name'=>"Basico_20MB",
            'price'=>"349",
           // 'partners'=>$this->faker->numberBetween( 0, 3),
            'tax_include'=>true,
            'tax'=>"16",
            'download_speed'=>"20000",
            'upload_speed'=>"5000",
            'guaranteed_speed_limit'=>"11",
            'priority'=>"Normal",
            'aggregation'=>"8",
            'burst'=>"100",
            'types_of_billing'=>$this->faker->randomElement(['Pagos Recurrentes', 'Prepagos (Diarios)','Prepagos (Personalizados)']),
            'prepaid_period'=>"Mensual"
        ];
    }
}
