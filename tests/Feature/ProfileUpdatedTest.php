<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('user can update profile and avatar', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'email' => 'old@example.com',
    ]);

    $this->actingAs($user);

    $response = $this->patch(route('profile.update'), [
        'name' => 'Nuevo Nombre',
        'email' => 'new@example.com',
        'foto_perfil' => UploadedFile::fake()->image('avatar.jpg'),
    ]);

    $response->assertRedirect(route('profile.edit'));
    $response->assertSessionHas('status', 'profile-updated');

    $user->refresh();

    expect($user->name)->toBe('Nuevo Nombre');
    expect($user->email)->toBe('new@example.com');
    expect($user->email_verified_at)->toBeNull();

    Storage::disk('public')->assertExists($user->foto_perfil);
});
