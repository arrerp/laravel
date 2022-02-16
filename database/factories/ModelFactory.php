
// Animal model factory

$factory->define(App\Animal::class, function (Faker\Generator $faker) {
    return [
        '' => $faker->words(2, true),
    ];
});

// TempEstados model factory

$factory->define(App\TempEstados::class, function (Faker\Generator $faker) {
    return [
        'id_estado' => $faker->randomNumber(),
        'cd_estado' => $faker->words(2, true),
    ];
});
