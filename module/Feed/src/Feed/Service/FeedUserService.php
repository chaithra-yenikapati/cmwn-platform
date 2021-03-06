<?php

namespace Feed\Service;

use Application\Exception\DuplicateEntryException;
use Application\Exception\NotFoundException;
use Application\Utils\ServiceTrait;
use Feed\FeedInterface;
use Feed\UserFeed;
use Feed\UserFeedInterface;
use User\UserInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Predicate\Operator;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\ArraySerializable;
use Zend\Paginator\Adapter\DbSelect;

/**
 * Class FeedUserService
 * @package Feed\Service
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FeedUserService implements FeedUserServiceInterface
{
    use ServiceTrait;

    /**
     * @var TableGateway $tableGateWay
     */
    protected $tableGateWay;

    /**
     * FeedUserService constructor.
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateWay = $tableGateway;
    }

    /**
     * @inheritdoc
     */
    public function attachFeedForUser($user, UserFeedInterface $feed)
    {
        try {
            $this->fetchFeedForUser($user, $feed->getFeedId());
            throw new DuplicateEntryException('User feed already exists');
        } catch (NotFoundException $nf) {
            $data = [
                'user_id' => $user instanceof UserInterface ? $user->getUserId() : $user,
                'feed_id' => $feed->getFeedId(),
                'read_flag' => 0,
            ];

            $this->tableGateWay->insert($data);
            return true;
        }
    }

    /**
     * @inheritdoc
     */
    public function fetchFeedForUser($user, string $feed, $where = null, UserFeedInterface $prototype = null)
    {
        $userId = $user instanceof UserInterface? $user->getUserId() : $user;
        $feedId = $feed instanceof FeedInterface? $feed->getFeedId() : $feed;

        $where = $this->createWhere($where);
        $where->addPredicate(new Operator('uf.user_id', Operator::OP_EQ, $userId));
        $where->addPredicate(new Operator('uf.feed_id', Operator::OP_EQ, $feedId));

        $select = new Select(['uf' => $this->tableGateWay->getTable()]);
        $select->join(
            ['f' => 'feed'],
            'uf.feed_id = f.feed_id'
        );
        $select->where($where);

        $rowSet = $this->tableGateWay->selectWith($select);
        $row = $rowSet->current();

        if (!$row) {
            throw new NotFoundException('Feed not found');
        }

        $userFeed = new UserFeed($row->getArrayCopy());
        return $userFeed;
    }

    /**
     * @inheritdoc
     */
    public function fetchAllFeedForUser($user, $where = null, UserFeedInterface $prototype = null)
    {
        $userId = $user instanceof UserInterface? $user->getUserId() : $user;
        $select = new Select(['uf' => $this->tableGateWay->getTable()]);
        $select->columns(['read_flag']);
        $select->join(
            ['f' => 'feed'],
            'uf.feed_id = f.feed_id'
        );

        $select->order(['f.priority DESC']);

        $where = $this->createWhere($where);
        $where->isNull('f.deleted');
        $where->addPredicate(new Operator('uf.user_id', Operator::OP_EQ, $userId));

        $select->where($where);

        $prototype = $prototype === null ? new UserFeed([]) : $prototype;

        $resultSet = new HydratingResultSet(new ArraySerializable(), $prototype);
        return new DbSelect($select, $this->tableGateWay->getAdapter(), $resultSet);
    }

    /**
     * @inheritdoc
     */
    public function updateFeedForUser($user, UserFeedInterface $feed)
    {
        $userId = $user instanceof UserInterface ? $user->getUserId() : $user;

        $data = ['read_flag' => $feed->getReadFlag()];

        $feed = $this->fetchFeedForUser($userId, $feed->getFeedId());

        $feed->setReadFlag($data['read_flag']);

        $this->tableGateWay->update($data, ['user_id' => $userId, 'feed_id' => $feed->getFeedId()]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteFeedForUser($user, UserFeedInterface $feed)
    {
        $userId = $user instanceof UserInterface ? $user->getUserId() : $user;

        $this->fetchFeedForUser($userId, $feed->getFeedId());
        $this->tableGateWay->delete(['user_id' => $userId, 'feed_id' => $feed->getFeedId()]);

        return true;
    }
}
