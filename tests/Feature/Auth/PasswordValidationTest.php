<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('rejects weak password during registration', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'weak@test.com',
        'password' => 'hola123',
        'password_confirmation' => 'hola123',
    ]);

    $response->assertSessionHasErrors('password');
});

test('accepts strong password during registration', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'strong@test.com',
        'password' => 'Strong!123Password',
        'password_confirmation' => 'Strong!123Password',
    ]);

    $response->assertSessionHasNoErrors();
});

test('fails if email is already registered even with strong password', function () {
    User::factory()->create([
        'email' => 'duplicate@test.com'
    ]);

    $response = $this->post('/register', [
        'name' => 'Another User',
        'email' => 'duplicate@test.com',
        'password' => 'Strong!123Password',
        'password_confirmation' => 'Strong!123Password',
    ]);

    $response->assertSessionHasErrors('email');
});
