<?php

namespace GameTest\Delegator;

use Game\Delegator\SaveGameDelegator;
use Game\SaveGame;
use Game\Service\SaveGameService;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;
use Zend\EventManager\Event;
use Zend\EventManager\EventManager;

/**
 * Test SaveGameDelegatorTest
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class SaveGameDelegatorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var \Mockery\MockInterface|\Game\Service\SaveGameService
     */
    protected $service;

    /**
     * @var SaveGameDelegator
     */
    protected $delegator;

    /**
     * @var array
     */
    protected $calledEvents = [];

    /**
     * @var
     */
    protected $saveGame;

    /**
     * @before
     */
    public function setUpDelegator()
    {
        $this->delegator    = new SaveGameDelegator($this->service, new EventManager());
        $this->delegator->getEventManager()->attach('*', [$this, 'captureEvents'], 1000000);
    }

    /**
     * @before
     */
    public function setUpService()
    {
        $this->service = \Mockery::mock(SaveGameService::class);
    }

    /**
     * @param Event $event
     */
    public function captureEvents(Event $event)
    {
        $this->calledEvents[] = [
            'name'   => $event->getName(),
            'target' => $event->getTarget(),
            'params' => $event->getParams(),
        ];
    }

    /**
     * @before
     */
    public function setUpSaveGame()
    {
        $this->saveGame = new SaveGame([
            'game_id' => 'monarch',
            'user_id' => 'manchuck',
            'data'    => ['foo' => 'bar', 'progress' => 100],
        ]);
    }

    /**
     * @test
     */
    public function testItShouldCallSaveGame()
    {
        $this->service->shouldReceive('saveGame')
            ->with($this->saveGame)
            ->andReturn(true)
            ->once();

        $this->delegator->saveGame($this->saveGame);

        $this->assertEquals(2, count($this->calledEvents));
        $this->assertEquals(
            [
                'name'   => 'save.user.game',
                'target' => $this->service,
                'params' => ['game_data' => $this->saveGame],
            ],
            $this->calledEvents[0]
        );

        $this->assertEquals(
            [
                'name'   => 'save.user.game.post',
                'target' => $this->service,
                'params' => ['game_data' => $this->saveGame],
            ],
            $this->calledEvents[1]
        );
    }

    /**
     * @test
     */
    public function testItShouldNotCallSaveGameWhenEventStops()
    {
        $this->service->shouldReceive('saveGame')
            ->never();

        $this->delegator->getEventManager()->attach('save.user.game', function (Event $event) {
            $event->stopPropagation(true);

            return true;
        });

        $this->assertTrue($this->delegator->saveGame($this->saveGame));

        $this->assertEquals(1, count($this->calledEvents));
        $this->assertEquals(
            [
                'name'   => 'save.user.game',
                'target' => $this->service,
                'params' => ['game_data' => $this->saveGame],
            ],
            $this->calledEvents[0]
        );
    }

    /**
     * @test
     */
    public function testItShouldCallDeleteSaveForUser()
    {
        $this->service->shouldReceive('deleteSaveForUser')
            ->with('manchuck', 'monarch')
            ->andReturn(true)
            ->once();

        $this->delegator->deleteSaveForUser('manchuck', 'monarch');

        $this->assertEquals(2, count($this->calledEvents));
        $this->assertEquals(
            [
                'name'   => 'delete.user.save.game',
                'target' => $this->service,
                'params' => ['user' => 'manchuck', 'game' => 'monarch'],
            ],
            $this->calledEvents[0]
        );

        $this->assertEquals(
            [
                'name'   => 'delete.user.save.game.post',
                'target' => $this->service,
                'params' => ['user' => 'manchuck', 'game' => 'monarch'],
            ],
            $this->calledEvents[1]
        );
    }

    /**
     * @test
     */
    public function testItShouldNotCallDeleteSaveForUserWhenEventStops()
    {
        $this->service->shouldReceive('deleteSaveForUser')
            ->never();

        $this->delegator->getEventManager()->attach('delete.user.save.game', function (Event $event) {
            $event->stopPropagation(true);

            return true;
        });

        $this->assertTrue($this->delegator->deleteSaveForUser('manchuck', 'monarch'));

        $this->assertEquals(1, count($this->calledEvents));
        $this->assertEquals(
            [
                'name'   => 'delete.user.save.game',
                'target' => $this->service,
                'params' => ['user' => 'manchuck', 'game' => 'monarch'],
            ],
            $this->calledEvents[0]
        );
    }

    /**
     * @test
     */
    public function testItShouldFetchSaveGameForUser()
    {
        $this->service->shouldReceive('fetchSaveGameForUser')
            ->with('manchuck', 'monarch', null, \Mockery::any())
            ->once()
            ->andReturn($this->saveGame);

        $this->delegator->fetchSaveGameForUser('manchuck', 'monarch');

        $this->assertEquals(2, count($this->calledEvents));
        $this->assertEquals(
            [
                'name'   => 'fetch.user.save.game',
                'target' => $this->service,
                'params' => [
                    'user'      => 'manchuck',
                    'game'      => 'monarch',
                    'prototype' => null,
                    'where'     => new Where(),
                ],
            ],
            $this->calledEvents[0]
        );

        $this->assertEquals(
            [
                'name'   => 'fetch.user.save.game.post',
                'target' => $this->service,
                'params' => [
                    'user'      => 'manchuck',
                    'game'      => 'monarch',
                    'prototype' => null,
                    'where'     => new Where(),
                    'game_data' => $this->saveGame,
                ],
            ],
            $this->calledEvents[1]
        );
    }

    /**
     * @test
     */
    public function testItShouldNotFetchSaveGameForUser()
    {
        $this->service->shouldReceive('fetchSaveGameForUser')
            ->never();

        $return = new \stdClass();

        $this->delegator->getEventManager()->attach('fetch.user.save.game', function (Event $event) use (&$return) {
            $event->stopPropagation(true);

            return $return;
        });

        $this->assertSame($return, $this->delegator->fetchSaveGameForUser('manchuck', 'monarch'));

        $this->assertEquals(1, count($this->calledEvents));
        $this->assertEquals(
            [
                'name'   => 'fetch.user.save.game',
                'target' => $this->service,
                'params' => [
                    'user'      => 'manchuck',
                    'game'      => 'monarch',
                    'prototype' => null,
                    'where'     => new Where(),
                ],
            ],
            $this->calledEvents[0]
        );
    }

    /**
     * @test
     */
    public function testItShouldFetchAllSaveGamesForUser()
    {
        $resultSet = new ResultSet();
        $resultSet->initialize($this->saveGame->getArrayCopy());
        $this->service->shouldReceive('fetchAllSaveGamesForUser')
            ->with('manchuck', null, null)
            ->andReturn($resultSet)
            ->once();
        $this->delegator->fetchAllSaveGamesForUser('manchuck');

        $this->assertEquals(2, count($this->calledEvents));
        $this->assertEquals(
            [
                'name'   => 'fetch.user.saves',
                'target' => $this->service,
                'params' => [
                    'user'      => 'manchuck',
                    'prototype' => null,
                    'where'     => null,
                ],
            ],
            $this->calledEvents[0]
        );

        $this->assertEquals(
            [
                'name'   => 'fetch.user.saves.post',
                'target' => $this->service,
                'params' => [
                    'user'       => 'manchuck',
                    'prototype'  => null,
                    'where'      => null,
                    'user-saves' => $resultSet,
                ],
            ],
            $this->calledEvents[1]
        );
    }

    /**
     * @test
     */
    public function testItShouldNotFetchSaveGamesForUserWhenEventStops()
    {
        $this->service->shouldReceive('fetchAllSaveGamesForUser')
            ->never();

        $return = new \stdClass();

        $this->delegator->getEventManager()->attach('fetch.user.saves', function (Event $event) use (&$return) {
            $event->stopPropagation(true);

            return $return;
        });

        $this->assertSame($return, $this->delegator->fetchAllSaveGamesForUser('manchuck'));

        $this->assertEquals(1, count($this->calledEvents));
        $this->assertEquals(
            [
                'name'   => 'fetch.user.saves',
                'target' => $this->service,
                'params' => [
                    'user'      => 'manchuck',
                    'prototype' => null,
                    'where'     => null,
                ],
            ],
            $this->calledEvents[0]
        );
    }

    /**
     * @test
     */
    public function testItShouldFetchAllSaveGameData()
    {
        $resultSet = new ResultSet();
        $resultSet->initialize($this->saveGame->getArrayCopy());
        $this->service->shouldReceive('fetchAllSaveGameData')
            ->with(null, null)
            ->andReturn($resultSet)
            ->once();
        $this->delegator->fetchAllSaveGameData();

        $this->assertEquals(2, count($this->calledEvents));
        $this->assertEquals(
            [
                'name'   => 'fetch.game-data',
                'target' => $this->service,
                'params' => [
                    'prototype' => null,
                    'where'     => null,
                ],
            ],
            $this->calledEvents[0]
        );

        $this->assertEquals(
            [
                'name'   => 'fetch.game-data.post',
                'target' => $this->service,
                'params' => [
                    'prototype' => null,
                    'where'     => null,
                    'game-data' => $resultSet,
                ],
            ],
            $this->calledEvents[1]
        );
    }

    /**
     * @test
     */
    public function testItShouldNotFetchAllSaveGameDataWhenEventStops()
    {
        $this->service->shouldReceive('fetchAllSaveGameData')
            ->never();

        $return = new \stdClass();

        $this->delegator->getEventManager()->attach('fetch.game-data', function (Event $event) use (&$return) {
            $event->stopPropagation(true);

            return $return;
        });

        $this->assertSame($return, $this->delegator->fetchAllSaveGameData());

        $this->assertEquals(1, count($this->calledEvents));
        $this->assertEquals(
            [
                'name'   => 'fetch.game-data',
                'target' => $this->service,
                'params' => [
                    'prototype' => null,
                    'where'     => null,
                ],
            ],
            $this->calledEvents[0]
        );
    }
}
