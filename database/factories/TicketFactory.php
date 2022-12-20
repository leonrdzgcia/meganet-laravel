<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_lead'=>$this->faker->randomDigitNotNull,
            'hidden'=>$this->faker->boolean,
            'assigned_to'=>$this->faker->randomDigitNotNull,
            'topic'=>$this->faker->sentence,
            'priority'=>$this->faker->randomElement(["Baja", "Normal", "Alta","Urgente"]),
            'status'=>$this->faker->randomElement(["nuevo", "trabajo en curso", "resuelto", "esperando al cliente", "esperando al agente"]),
            'group'=>$this->faker->randomElement(["cualquier", "IT", "finanzas", "ventas"]),
            'type'=>$this->faker->randomElement(["Pregunta", "Incidente", "Problema", "Solicitud de Función", "Cliente Potencial"]),
            //'mensaje'=>$this->faker->text,
            'prospect_name'=>$this->faker->firstName,
            'date_time'=>$this->faker->dateTime,
            'phone'=>$this->faker->e164PhoneNumber,
            'address'=>$this->faker->address,



        ];
    }
}
