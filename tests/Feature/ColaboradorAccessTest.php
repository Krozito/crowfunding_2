<?php
#
use App\Models\Proyecto;
use App\Models\User;

test('guest cannot access collaborator dashboard', function () {
    $response = $this->get('/colaborador');

    $response->assertRedirect('/login');
});
