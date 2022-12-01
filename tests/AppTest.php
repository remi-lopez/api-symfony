<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

# LINE COMMAND : php bin/phpunit
class AppTest extends TestCase
{
    public function testAppIsRunning(): void
    {
        $this->assertEquals(4, 2 + 2);
    }
}
