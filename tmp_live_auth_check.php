<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = Illuminate\Support\Facades\DB::table('users')->where('email', 'admin@unilsched.test')->first();

var_export([
    'exists' => (bool) $user,
    'password_hash' => $user->password ?? null,
    'is_admin' => $user->is_admin ?? null,
    'attempt' => Illuminate\Support\Facades\Auth::attempt([
        'email' => 'admin@unilsched.test',
        'password' => 'Admin1234!',
    ]),
]);

echo PHP_EOL;
