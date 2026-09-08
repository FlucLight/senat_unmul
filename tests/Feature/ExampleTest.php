<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_root_redirects_to_login_when_not_authenticated(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }
}