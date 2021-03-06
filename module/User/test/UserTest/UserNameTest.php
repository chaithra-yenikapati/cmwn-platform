<?php

namespace UserTest;

use PHPUnit\Framework\TestCase;
use User\UserName;

/**
 * Test UserNameTest
 *
 * @group User
 * @group Names
 */
class UserNameTest extends TestCase
{
    /**
     * @test
     */
    public function testItShouldAddLeftAndRightNumbers()
    {
        $userName = new UserName('foo', 'bar');

        $userName->setValues(1, 1);
        $this->assertEquals('foo-bar002', $userName->userName);

        $userName->setValues(5, 8);
        $this->assertEquals('foo-bar013', $userName->userName);
    }

    /**
     * @test
     */
    public function testItShouldKeepLeftAndRightValuesAboveOne()
    {
        $userName = new UserName('foo', 'bar');

        $userName->setValues(0, 0);
        $this->assertEquals('foo-bar002', $userName->userName);
    }

    /**
     * @test
     */
    public function testItShouldThrowExceptionWhenAccessingInvalidProperty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Property: foo');

        $userName = new UserName('foo', 'bar');
        $userName->foo;
    }
}
