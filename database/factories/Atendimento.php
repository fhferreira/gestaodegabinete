<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Atendimento;
use Faker\Generator as Faker;

$factory->define(Atendimento::class, function (Faker $faker) {
    return [
        'dat_atendimento' => $faker->date(),
        'txt_detalhes' => $faker->text,
        'nom_usuario_log' => "admin",
        'nom_operacao_log' => "insert",
        'ind_status' => "A",
        'GAB_PESSOA_cod_pessoa' => $faker->numberBetween(1,100),
        'GAB_TIPO_ATENDIMENTO_cod_tipo' => $faker->numberBetween(1,4),
        'GAB_STATUS_ATENDIMENTO_cod_status' => $faker->numberBetween(1,3),
    ];
});
