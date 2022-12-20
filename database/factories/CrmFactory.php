<?php

namespace Database\Factories;

use App\Models\Crm;
use Illuminate\Database\Eloquent\Factories\Factory;

class CrmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Crm::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'usuario'=>$this->faker->userName,
            'contrasena'=>$this->faker->password,
            'nombre'=>$this->faker->firstNameMale,
            'email'=>$this->faker->email,
            'telefono'=>$this->faker->e164PhoneNumber,
            'ubicacion'=>$this->faker->randomElement(["sector 192.168.1.1","sector 10.28.13.1", "sector 135.20.1.1"]),
            'geodata'=>$this->faker->longitude,
            'fecha_alta'=>$this->faker->dateTime,
            'calle'=>$this->faker->streetAddress,
            'codigo_zip'=>$this->faker->postcode,
            'ciudad'=>$this->faker->city,
            'score'=>$this->faker->randomDigitNotNull,
            'last_contacted'=>$this->faker->dateTime,
            'crm_status'=>$this->faker->randomElement(["Nuevo","Contactado","Interesado","Cotización","Ganado","Perdido"]),
            'types_of_billing'=>$this->faker->randomElement(['Pagos Recurrentes', 'Prepagos (Diarios)','Prepagos (Personalizados)'])

        ];
    }
}
