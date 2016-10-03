<?php

namespace Suggest\Rule;

use Friend\Service\FriendService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FriendRuleFactory
 * @package Suggest\Rule
 */
class FriendRuleFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $friendService = $serviceLocator->get(FriendService::class);
        return new FriendRule($friendService);
    }
}
