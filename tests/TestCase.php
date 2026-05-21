<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $token = Str::random(40);

        $this->withSession(['_token' => $token]);
        $this->withHeader('X-CSRF-TOKEN', $token);
    }
}
