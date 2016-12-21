<?php

namespace Security\Factory;

use Group\Service\UserGroupService;
use Group\Service\UserGroupServiceInterface;
use Security\Listeners\OrgServiceListener;
use Security\Service\SecurityOrgServiceInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class OrgServiceListenerFactory
 * @package Security\Factory
 */
class OrgServiceListenerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var SecurityOrgServiceInterface $securityOrgService */
        $securityOrgService = $serviceLocator->get(SecurityOrgServiceInterface::class);
        /**@var UserGroupServiceInterface $userGroupService */
        $userGroupService = $serviceLocator->get(UserGroupService::class);
        return new OrgServiceListener($securityOrgService, $userGroupService);
    }
}
