<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Enums\PaymentStatus;
use App\Models\Payment;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Payment::class, function (Faker $faker) {
    // Generating random status
    $status_array = [PaymentStatus::PENDING()->getValue(), PaymentStatus::PAID()->getValue()];
    $status       = $status_array[$faker->numberBetween(0, 1)];

    // Generate random dates
    $payment_date = PaymentStatus::PENDING()->getValue() === $status ? null : $faker->date();
    $expires_at   = $payment_date
        ? Carbon::createFromFormat('Y-m-d', $payment_date)->addMonths($faker->numberBetween(1, 3))
        : $faker->date();

    return [
        'uuid'         => Str::uuid(),
        'payment_date' => $payment_date,
        'expires_at'   => $expires_at,
        'status'       => $status,
        'user_id'      => $faker->numberBetween(1, 50),
        'clp_usd'      => $faker->randomDigitNotNull,
        'created_at'   => $faker->date('Y-m-d H:i:s'),
        'updated_at'   => $faker->date('Y-m-d H:i:s')
    ];
});
