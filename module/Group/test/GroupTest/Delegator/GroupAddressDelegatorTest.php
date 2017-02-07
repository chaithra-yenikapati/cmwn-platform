<?php

namespace GroupTest\Delegator;

use Address\Address;
use Application\Exception\NotFoundException;
use Group\Delegator\GroupAddressDelegator;
use Group\Service\GroupAddressService;
use Group\Group;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use \PHPUnit_Framework_TestCase as TestCase;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Select;
use Zend\EventManager\Event;
use Zend\EventManager\EventManager;
use Zend\Paginator\Adapter\DbSelect;

/**
 * Class GroupAddressDelegatorTest
 * @package AddressTest\Delegator
 * @SuppressWarnings(PHPMD)
 */
class GroupAddressDelegatorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var \Mockery\MockInterface|GroupAddressService
     */
    protected $addressService;

    /**
     * @var \Mockery\MockInterface|Adapter
     */
    protected $adapter;

    /**
     * @var GroupAddressDelegator
     */
    protected $delegator;

    /**
     * @var array
     */
    protected $calledEvents = [];

    /**
     * @before
     */
    public function setUpService()
    {
        $this->addressService = \Mockery::mock(GroupAddressService::class);
    }

    /**
     * @before
     */
    public function setUpAdapter()
    {
        $this->adapter = \Mockery::mock(Adapter::class);
        $this->adapter->shouldReceive('getPlatform')->byDefault();
    }

    /**
     * @before
     */
    public function setUpDelegator()
    {
        $events             = new EventManager();
        $this->calledEvents = [];
        $this->delegator    = new GroupAddressDelegator($this->addressService, $events);
        $events->attach('*', [$this, 'captureEvents'], PHP_INT_MAX);
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
     * @test
     */
    public function testItShouldCallAttachAddress()
    {
        $address = new Address([
            'address_id' => 'foo'
        ]);

        $group = new Group(['group_id' => 'bar']);

        $this->addressService->shouldReceive('attachAddressToGroup')
            ->andReturn(true)->once();

        $this->delegator->attachAddressToGroup($group, $address);

        $this->assertEquals(
            2,
            count($this->calledEvents),
            GroupAddressDelegator::class . ' did not trigger the correct number of events'
        );

        $this->assertEquals(
            [
                'name'   => 'attach.group.address',
                'target' => $this->addressService,
                'params' => ['group' => $group, 'address' => $address],
            ],
            $this->calledEvents[0],
            GroupAddressDelegator::class . ' did not trigger the event correctly'
        );

        $this->assertEquals(
            [
                'name'   => 'attach.group.address.post',
                'target' => $this->addressService,
                'params' => ['group' => $group, 'address' => $address, 'return' => true],
            ],
            $this->calledEvents[1],
            GroupAddressDelegator::class . ' did not trigger the event correctly for attach.group.address.post'
        );
    }

    /**
     * @test
     */
    public function testItShouldNotCallAttachAddressWhenEventStops()
    {
        $address = new Address([
            'address_id' => 'foo'
        ]);

        $group = new Group(['group_id' => 'bar']);

        $this->addressService->shouldReceive('attachAddressToGroup')
            ->andReturn(true)->never();

        $this->delegator->getEventManager()->attach('attach.group.address', function (Event $event) {
            $event->stopPropagation(true);

            return false;
        });

        $this->delegator->attachAddressToGroup($group, $address);

        $this->assertEquals(
            1,
            count($this->calledEvents),
            GroupAddressDelegator::class . ' did not trigger the correct number of events'
        );

        $this->assertEquals(
            [
                'name'   => 'attach.group.address',
                'target' => $this->addressService,
                'params' => ['group' => $group, 'address' => $address],
            ],
            $this->calledEvents[0],
            GroupAddressDelegator::class . ' did not trigger the event correctly'
        );
    }

    /**
     * @test
     */
    public function testItShouldTriggerErrorEventWhenExceptionOnAttachAddress()
    {
        $address = new Address([
            'address_id' => 'foo'
        ]);

        $group = new Group(['group_id' => 'bar']);

        $this->addressService->shouldReceive('attachAddressToGroup')
            ->andReturnUsing(function () {
                throw new \Exception();
            })->once();

        try {
            $this->delegator->attachAddressToGroup($group, $address);
            $this->fail('exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals(
                2,
                count($this->calledEvents),
                GroupAddressDelegator::class . ' did not trigger the correct number of events'
            );

            $this->assertEquals(
                [
                    'name' => 'attach.group.address',
                    'target' => $this->addressService,
                    'params' => ['group' => $group, 'address' => $address],
                ],
                $this->calledEvents[0],
                GroupAddressDelegator::class . ' did not trigger the event correctly'
            );

            $this->assertEquals(
                [
                    'name' => 'attach.group.address.error',
                    'target' => $this->addressService,
                    'params' => ['group' => $group, 'address' => $address, 'exception' => new \Exception()],
                ],
                $this->calledEvents[1],
                GroupAddressDelegator::class . ' did not trigger the event correctly for attach.group.address.error'
            );
        }
    }

    /**
     * @test
     */
    public function testItShouldCallDetachddress()
    {
        $address = new Address([
            'address_id' => 'foo'
        ]);

        $group = new Group(['group_id' => 'bar']);

        $this->addressService->shouldReceive('detachAddressFromGroup')
            ->andReturn(true)->once();

        $this->delegator->detachAddressFromGroup($group, $address);

        $this->assertEquals(
            2,
            count($this->calledEvents),
            GroupAddressDelegator::class . ' did not trigger the correct number of events'
        );

        $this->assertEquals(
            [
                'name'   => 'detach.group.address',
                'target' => $this->addressService,
                'params' => ['group' => $group, 'address' => $address],
            ],
            $this->calledEvents[0],
            GroupAddressDelegator::class . ' did not trigger the event correctly'
        );

        $this->assertEquals(
            [
                'name'   => 'detach.group.address.post',
                'target' => $this->addressService,
                'params' => ['group' => $group, 'address' => $address, 'return' => true],
            ],
            $this->calledEvents[1],
            GroupAddressDelegator::class . ' did not trigger the event correctly for detach.group.address.post'
        );
    }

    /**
     * @test
     */
    public function testItShouldNotCallDetachAddressWhenEventStops()
    {
        $address = new Address([
            'address_id' => 'foo'
        ]);

        $group = new Group(['group_id' => 'bar']);

        $this->addressService->shouldReceive('detachAddressFromGroup')
            ->andReturn(true)->never();

        $this->delegator->getEventManager()->attach('detach.group.address', function (Event $event) {
            $event->stopPropagation(true);

            return false;
        });

        $this->delegator->detachAddressFromGroup($group, $address);

        $this->assertEquals(
            1,
            count($this->calledEvents),
            GroupAddressDelegator::class . ' did not trigger the correct number of events'
        );

        $this->assertEquals(
            [
                'name'   => 'detach.group.address',
                'target' => $this->addressService,
                'params' => ['group' => $group, 'address' => $address],
            ],
            $this->calledEvents[0],
            GroupAddressDelegator::class . ' did not trigger the event correctly'
        );
    }

    /**
     * @test
     */
    public function testItShouldTriggerErrorEventOnExceptionWhileDetachAddress()
    {
        $address = new Address([
            'address_id' => 'foo'
        ]);

        $group = new Group(['group_id' => 'bar']);

        $this->addressService->shouldReceive('detachAddressFromGroup')
            ->andReturnUsing(function () {
                throw new \Exception();
            })->once();

        try {
            $this->delegator->detachAddressFromGroup($group, $address);
            $this->fail('exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals(
                2,
                count($this->calledEvents),
                GroupAddressDelegator::class . ' did not trigger the correct number of events'
            );

            $this->assertEquals(
                [
                    'name' => 'detach.group.address',
                    'target' => $this->addressService,
                    'params' => ['group' => $group, 'address' => $address],
                ],
                $this->calledEvents[0],
                GroupAddressDelegator::class . ' did not trigger the event correctly'
            );

            $this->assertEquals(
                [
                    'name' => 'detach.group.address.error',
                    'target' => $this->addressService,
                    'params' => ['group' => $group, 'address' => $address, 'exception' => new \Exception()],
                ],
                $this->calledEvents[1],
                GroupAddressDelegator::class . ' did not trigger the event correctly for detach.group.address.error'
            );
        }
    }

    /**
     * @test
     */
    public function testItShouldCallFetchAllAddressesForGroup()
    {
        $dbSelect = new DbSelect(new Select(), $this->adapter, new HydratingResultSet());

        $group = new Group(['group_id' => 'bar']);

        $this->addressService->shouldReceive('fetchAllAddressesForGroup')
            ->andReturn($dbSelect)->once();

        $this->delegator->fetchAllAddressesForGroup($group);

        $this->assertEquals(
            2,
            count($this->calledEvents),
            GroupAddressDelegator::class . ' did not trigger the correct number of events'
        );

        $this->assertEquals(
            [
                'name'   => 'fetch.all.group.addresses',
                'target' => $this->addressService,
                'params' => ['group' => $group, 'where' => null, 'prototype' => null],
            ],
            $this->calledEvents[0],
            GroupAddressDelegator::class . ' did not trigger the event correctly'
        );

        $this->assertEquals(
            [
                'name'   => 'fetch.all.group.addresses.post',
                'target' => $this->addressService,
                'params' => ['group' => $group, 'where' => null, 'prototype' => null, 'addresses' => $dbSelect],
            ],
            $this->calledEvents[1],
            GroupAddressDelegator::class . ' did not trigger the event correctly for fetch.all.group.addresses.post'
        );
    }

    /**
     * @test
     */
    public function testItShouldNotCallFetchAllAddressesForGroupWhenEventStops()
    {
        $dbSelect = new DbSelect(new Select(), $this->adapter, new HydratingResultSet());

        $group = new Group(['group_id' => 'bar']);

        $this->addressService->shouldReceive('fetchAllAddressesForGroup')
            ->andReturn(true)->never();

        $this->delegator
            ->getEventManager()
            ->attach('fetch.all.group.addresses', function (Event $event) use (&$dbSelect) {
                $event->stopPropagation(true);

                return $dbSelect;
            });

        $this->delegator->fetchAllAddressesForGroup($group);

        $this->assertEquals(
            1,
            count($this->calledEvents),
            GroupAddressDelegator::class . ' did not trigger the correct number of events'
        );

        $this->assertEquals(
            [
                'name'   => 'fetch.all.group.addresses',
                'target' => $this->addressService,
                'params' => ['group' => $group, 'where' => null, 'prototype' => null],
            ],
            $this->calledEvents[0],
            GroupAddressDelegator::class . ' did not trigger the event correctly'
        );
    }

    /**
     * @test
     */
    public function testItShouldTriggerErrorEventOnExceptionWhileFetchAllGroupAddresses()
    {
        $group = new Group(['group_id' => 'bar']);

        $this->addressService->shouldReceive('fetchAllAddressesForGroup')
            ->andReturnUsing(function () {
                throw new \Exception();
            })->once();
        try {
            $this->delegator->fetchAllAddressesForGroup($group);
            $this->fail('exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals(
                2,
                count($this->calledEvents),
                GroupAddressDelegator::class . ' did not trigger the correct number of events'
            );

            $this->assertEquals(
                [
                    'name' => 'fetch.all.group.addresses',
                    'target' => $this->addressService,
                    'params' => ['group' => $group, 'where' => null, 'prototype' => null],
                ],
                $this->calledEvents[0],
                GroupAddressDelegator::class . ' did not trigger the event correctly'
            );

            $this->assertEquals(
                [
                    'name' => 'fetch.all.group.addresses.error',
                    'target' => $this->addressService,
                    'params' => [
                        'group' => $group,
                        'where' => null,
                        'prototype' => null,
                        'exception' => new \Exception
                    ],
                ],
                $this->calledEvents[1],
                GroupAddressDelegator::class . ' did not trigger the correct event for fetch.all.group.addresses.error'
            );
        }
    }

    /**
     * @test
     */
    public function testItShouldCallFetchAddressForGroup()
    {
        $address = new Address([
            'address_id' => 'foo'
        ]);

        $group = new Group(['group_id' => 'bar']);

        $this->addressService->shouldReceive('fetchAddressForGroup')
            ->andReturn($address)->once();

        $this->delegator->fetchAddressForGroup($group, $address);

        $this->assertEquals(
            2,
            count($this->calledEvents),
            GroupAddressDelegator::class . ' did not trigger the correct number of events'
        );

        $this->assertEquals(
            [
                'name'   => 'fetch.group.address',
                'target' => $this->addressService,
                'params' => ['group' => $group, 'address' => $address],
            ],
            $this->calledEvents[0],
            GroupAddressDelegator::class . ' did not trigger the event correctly'
        );

        $this->assertEquals(
            [
                'name'   => 'fetch.group.address.post',
                'target' => $this->addressService,
                'params' => ['group' => $group, 'address' => $address],
            ],
            $this->calledEvents[1],
            GroupAddressDelegator::class . ' did not trigger the event correctly for fetch.group.address.post'
        );
    }

    /**
     * @test
     */
    public function testItShouldNotCallFetchAddressForGroupWhenEventStops()
    {
        $address = new Address([
            'address_id' => 'foo'
        ]);

        $group = new Group(['group_id' => 'bar']);

        $this->addressService->shouldReceive('fetchAddressForGroup')
            ->andReturn(true)->never();

        $this->delegator->getEventManager()->attach('fetch.group.address', function (Event $event) {
            $event->stopPropagation(true);

            return false;
        });

        $this->delegator->fetchAddressForGroup($group, $address);

        $this->assertEquals(
            1,
            count($this->calledEvents),
            GroupAddressDelegator::class . ' did not trigger the correct number of events'
        );

        $this->assertEquals(
            [
                'name'   => 'fetch.group.address',
                'target' => $this->addressService,
                'params' => ['group' => $group, 'address' => $address],
            ],
            $this->calledEvents[0],
            GroupAddressDelegator::class . ' did not trigger the event correctly'
        );
    }

    /**
     * @test
     */
    public function testItShouldTriggerErrorEventOnExceptionWhileFetchingGroupAddress()
    {
        $address = new Address([
            'address_id' => 'foo'
        ]);

        $group = new Group(['group_id' => 'bar']);

        $this->addressService->shouldReceive('fetchAddressForGroup')
            ->andReturnUsing(function () {
                throw new NotFoundException();
            })->once();

        try {
            $this->delegator->fetchAddressForGroup($group, $address);
            $this->fail('exception is not thrown');
        } catch (\Exception $e) {
            $this->assertEquals(
                2,
                count($this->calledEvents),
                GroupAddressDelegator::class . ' did not trigger the correct number of events'
            );

            $this->assertEquals(
                [
                    'name' => 'fetch.group.address',
                    'target' => $this->addressService,
                    'params' => ['group' => $group, 'address' => $address],
                ],
                $this->calledEvents[0],
                GroupAddressDelegator::class . ' did not trigger the event correctly'
            );

            $this->assertEquals(
                [
                    'name' => 'fetch.group.address.error',
                    'target' => $this->addressService,
                    'params' => ['group' => $group, 'address' => $address, 'exception' => new NotFoundException()],
                ],
                $this->calledEvents[1],
                GroupAddressDelegator::class . ' did not trigger the event correctly for fetch.group.address.error'
            );
        }
    }
}