<?php

namespace Api\Factory;

use Api\Listeners\UserHalLinksListener;
use Security\Service\SecurityGroupServiceInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ResetHalLinkListener
 */
class UserHalLinksListenerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var SecurityGroupServiceInterface $groupService */
        $groupService = $serviceLocator->get(SecurityGroupServiceInterface::class);
        return new UserHalLinksListener($groupService);
    }
}