<?php

test('welcome page is accessible', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
