<?php

namespace Group\Delegator;

use Application\Exception\NotFoundException;
use Application\Utils\HideDeletedEntitiesListener;
use Application\Utils\ServiceTrait;
use Group\Service\GroupService;
use Group\Service\GroupServiceInterface;
use Group\GroupInterface;
use User\User;
use User\UserInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Predicate\PredicateInterface;
use Zend\Db\Sql\Where;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Paginator\Adapter\DbSelect;

/**
 * Class GroupServiceDelegator
 * @package Group\Delegator
 */
class GroupDelegator implements GroupServiceInterface
{
    use EventManagerAwareTrait;
    use ServiceTrait;

    /**
     * @var GroupService
     */
    protected $realService;

    /**
     * @var string
     */
    protected $eventIdentifier = 'Group\Service\GroupServiceInterface';

    /**
     * GroupDelegator constructor.
     * @param GroupService $service
     */
    public function __construct(GroupService $service)
    {
        $this->realService = $service;
    }

    protected function attachDefaultListeners()
    {
        $hideListener = new HideDeletedEntitiesListener(['fetch.all.groups'], ['fetch.group.post']);
        $hideListener->setEntityParamKey('group');

        $this->getEventManager()->attach($hideListener);
    }

    /**
     * @param GroupInterface $parent
     * @param GroupInterface $child
     * @return bool
     */
    public function addChildToGroup(GroupInterface $parent, GroupInterface $child)
    {
        $this->realService->addChildToGroup($parent, $child);
    }

    /**
     * Saves a group
     *
     * If the group id is null, then a new group is created
     *
     * @param GroupInterface $group
     * @return bool
     * @throws NotFoundException
     */
    public function saveGroup(GroupInterface $group)
    {
        $event    = new Event('save.group', $this->realService, ['group' => $group]);
        $response = $this->getEventManager()->trigger($event);

        if ($response->stopped()) {
            return $response->last();
        }

        $return = $this->realService->saveGroup($group);

        $event    = new Event('save.group.post', $this->realService, ['group' => $group]);
        $this->getEventManager()->trigger($event);

        return $return;

    }

    /**
     * Fetches one group from the DB using the id
     *
     * @param $groupId
     * @return GroupInterface
     * @throws NotFoundException
     */
    public function fetchGroup($groupId)
    {
        $event    = new Event('fetch.group', $this->realService, ['group_id' => $groupId]);
        $response = $this->getEventManager()->trigger($event);

        if ($response->stopped()) {
            return $response->last();
        }

        $return = $this->realService->fetchGroup($groupId);
        $event    = new Event('fetch.group.post', $this->realService, ['group_id' => $groupId, 'group' => $return]);
        $this->getEventManager()->trigger($event);
        return $return;
    }

    /**
     * Fetches on group from the DB by using the external id
     *
     * @param $externalId
     * @return GroupInterface
     * @throws NotFoundException
     */
    public function fetchGroupByExternalId($externalId)
    {
        $event    = new Event('fetch.group.external', $this->realService, ['external_id' => $externalId]);
        $response = $this->getEventManager()->trigger($event);

        if ($response->stopped()) {
            return $response->last();
        }

        $return = $this->realService->fetchGroupByExternalId($externalId);
        $event  = new Event(
            'fetch.group.external.post',
            $this->realService,
            ['external_id' => $externalId, 'group' => $return]
        );
        $this->getEventManager()->trigger($event);
        return $return;
    }

    /**
     * Deletes a group from the database
     *
     * Soft deletes unless soft is false
     *
     * @param GroupInterface $group
     * @param bool $soft
     * @return bool
     */
    public function deleteGroup(GroupInterface $group, $soft = true)
    {
        $event    = new Event('delete.group', $this->realService, ['group' => $group, 'soft' => $soft]);
        $response = $this->getEventManager()->trigger($event);

        if ($response->stopped()) {
            return $response->last();
        }

        $return = $this->realService->deleteGroup($group, $soft);
        $event  = new Event('delete.group.post', $this->realService, ['group' => $group, 'soft' => $soft]);
        $this->getEventManager()->trigger($event);
        return $return;
    }

    /**
     * @param null|PredicateInterface|array $where
     * @param bool $paginate
     * @param null|object $prototype
     * @return HydratingResultSet|DbSelect
     */
    public function fetchAll($where = null, $paginate = true, $prototype = null)
    {
        $where = $this->createWhere($where);
        $event    = new Event(
            'fetch.all.groups',
            $this->realService,
            ['where' => $where, 'paginate' => $paginate, 'prototype' => $prototype]
        );

        $response = $this->getEventManager()->trigger($event);
        if ($response->stopped()) {
            return $response->last();
        }

        $return   = $this->realService->fetchAll($where, $paginate, $prototype);
        $event    = new Event(
            'fetch.all.groups.post',
            $this->realService,
            ['where' => $where, 'paginate' => $paginate, 'prototype' => $prototype, 'groups' => $return]
        );
        $this->getEventManager()->trigger($event);

        return $return;
    }

    /**
     * @param GroupInterface|string|Where $user
     * @param null $where
     * @param bool $paginate
     * @param null $prototype
     * @return mixed|HydratingResultSet|DbSelect
     */
    public function fetchAllForUser($user, $where = null, $paginate = true, $prototype = null)
    {
        $where = $this->createWhere($where);
        $event    = new Event(
            'fetch.user.groups',
            $this->realService,
            ['user' => $user, 'where' => $where, 'paginate' => $paginate, 'prototype' => $prototype]
        );

        $response = $this->getEventManager()->trigger($event);
        if ($response->stopped()) {
            return $response->last();
        }

        $return   = $this->realService->fetchAll($where, $paginate, $prototype);
        $event->setName('fetch.user.groups.post');
        $event->setParam('result', $return);
        $this->getEventManager()->trigger($event);

        return $return;
    }


}
