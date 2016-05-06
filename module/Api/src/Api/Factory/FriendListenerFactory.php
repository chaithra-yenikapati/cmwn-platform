<?php

namespace Api\Factory;

use Api\Listeners\FriendListener;
use Friend\Service\FriendServiceInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FriendListenerFactory
 */
class FriendListenerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var FriendServiceInterface $friendService */
        $friendService = $serviceLocator->get(FriendServiceInterface::class);
        return new FriendListener($friendService);
    }
}