<?php

namespace Test\Service;

use PHPUnit\Framework\TestCase;
use App\Service\CheckUser;

class CheckUserTest extends TestCase
{
    public function testCheckLogin()
    {
        $login = new CheckUser();
        $this->assertEquals(True, $login->checkLogin());
    }
}
