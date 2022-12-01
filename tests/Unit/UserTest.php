<?php

namespace App\Tests\Unit;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }

    public function testValidUser(): void
    {
        $kernel = self::bootKernel();

        $user = new User();

        $errors = static::getContainer()
            ->get("validator")->validate($user);

        foreach ($errors as $error) {
            dump($error->getPropertyPath(),
                $error->getMessage());
        }
    }
}
