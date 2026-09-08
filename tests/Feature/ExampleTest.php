<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_root_returns_welcome_page_for_guest(): void
    {
        $this->get('/')->assertOk();
    }
}