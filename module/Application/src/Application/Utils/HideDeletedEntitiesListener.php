<?php

namespace Application\Utils;

use Application\Exception\NotFoundException;
use Zend\Db\Sql\Predicate\IsNull;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

/**
 * Class HideDeletedUsersListener
 *
 * @todo Allow some users to be able to see deleted users
 * @todo Make this class more genric
 * @package User\Delegator
 */
class HideDeletedEntitiesListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @var string name of the deleted field in the table
     */
    protected $deletedField = 'deleted';

    /**
     * @var array events to listen too to add a where
     */
    protected $whereEvents = [];

    /**
     * @var string name of the event param key that will contain the where
     */
    protected $whereParamKey = 'where';

    /**
     * @var string name of the param key to fetch the entity
     */
    protected $entityParamKey = 'entity';

    /**
     * @var array events to listen on that will produce an entity
     */
    protected $entityEvents = [];

    /**
     * HideDeletedEntitiesListener constructor.
     *
     * @param array $whereEvents
     * @param array $entityEvents
     */
    public function __construct(array $whereEvents, array $entityEvents)
    {
        $this->whereEvents  = $whereEvents;
        $this->entityEvents = $entityEvents;
    }

    /**
     * @param string $deletedField
     * @return HideDeletedEntitiesListener
     */
    public function setDeletedField($deletedField)
    {
        $this->deletedField = $deletedField;

        return $this;
    }

    /**
     * @param array $whereEvents
     * @return HideDeletedEntitiesListener
     */
    public function setWhereEvents($whereEvents)
    {
        $this->whereEvents = $whereEvents;

        return $this;
    }

    /**
     * @param string $whereParamKey
     * @return HideDeletedEntitiesListener
     */
    public function setWhereParamKey($whereParamKey)
    {
        $this->whereParamKey = $whereParamKey;

        return $this;
    }

    /**
     * @param string $entityParamKey
     * @return HideDeletedEntitiesListener
     */
    public function setEntityParamKey($entityParamKey)
    {
        $this->entityParamKey = $entityParamKey;

        return $this;
    }

    /**
     * @param array $entityEvents
     * @return HideDeletedEntitiesListener
     */
    public function setEntityEvents($entityEvents)
    {
        $this->entityEvents = $entityEvents;

        return $this;
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        foreach ($this->whereEvents as $eventName) {
            $this->listeners[] = $events->attach($eventName, [$this, 'addPredicateToWhere']);
        }

        foreach ($this->entityEvents as $eventName) {
            $this->listeners[] = $events->attach($eventName, [$this, 'hideEntity']);
        }
    }

    /**
     * Adds the where exclusion
     *
     * @param Event $event
     */
    public function addPredicateToWhere(Event $event)
    {
        $where = $event->getParam($this->whereParamKey);
        if (!$where instanceof PredicateSet) {
            return;
        }

        $where->addPredicate(new IsNull($this->deletedField));
    }

    /**
     * Checks if the entity is deleted and throws not found
     *
     * @param Event $event
     * @throws NotFoundException
     */
    public function hideEntity(Event $event)
    {
        $user = $event->getParam($this->entityParamKey);
        if (!$user instanceof SoftDeleteInterface) {
            return;
        }

        if ($user->isDeleted()) {
            throw new NotFoundException('Entity not found');
        }
    }
}
