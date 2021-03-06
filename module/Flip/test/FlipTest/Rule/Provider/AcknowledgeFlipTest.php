<?php

namespace FlipTest\Rule\Provider;

use Flip\EarnedFlip;
use Flip\Exception\RuntimeException;
use Flip\Rule\Provider\AcknowledgeFlip;
use PHPUnit\Framework\TestCase as TestCase;

/**
 * Test AcknowledgeFlipTest
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AcknowledgeFlipTest extends TestCase
{
    /**
     * @test
     */
    public function testItShouldProvideFlipWithDefaultName()
    {
        $flip = new EarnedFlip();
        $flip->setAcknowledgeId('foo-bar');

        $provider = new AcknowledgeFlip($flip);

        $this->assertEquals(
            'acknowledge_flip',
            $provider->getName(),
            AcknowledgeFlip::class . ' set the wrong default name'
        );

        $this->assertSame(
            $flip,
            $provider->getValue(),
            AcknowledgeFlip::class . ' did not return the eanred flip'
        );
    }

    /**
     * @test
     */
    public function testItShouldProvideFlipWithCustomName()
    {
        $flip = new EarnedFlip();
        $flip->setAcknowledgeId('foo-bar');

        $provider = new AcknowledgeFlip($flip, 'manchuck');

        $this->assertEquals(
            'manchuck',
            $provider->getName(),
            AcknowledgeFlip::class . ' set the wrong name'
        );

        $this->assertSame(
            $flip,
            $provider->getValue(),
            AcknowledgeFlip::class . ' did not return the eanred flip'
        );
    }

    /**
     * @test
     */
    public function testItShouldThrowExceptionWhenFlipIsAcknowledged()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Flip "foo-bar" for user "fizz-buzz" has already been acknowledge'
        );
        $flip     = new EarnedFlip();
        $flip->setEarnedBy('fizz-buzz');
        $flip->setFlipId('foo-bar');
        $provider = new AcknowledgeFlip($flip);
    }
}
