<?php

namespace Security\Service;

use Org\Organization;
use Security\SecurityUser;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\In;
use Zend\Db\Sql\Predicate\Operator;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

/**
 * Class SecurityOrgService
 *
 * @package Security\Service
 */
class SecurityOrgService
{
    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * SecurityOrgService constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Attaches the 1st 10 organizations to a logged in user
     *
     * SELECT
     *   DISTINCT(o.org_id) AS org_id,
     *   o.type AS org_type,
     *   o.description AS org_descrption
     * FROM organizations o
     *   LEFT JOIN groups g ON o.org_id = g.organization_id
     *   LEFT JOIN user_groups ug ON ug.group_id = g.group_id
     * WHERE ug.user_id = 'b4e9147a-e60a-11e5-b8ea-0800274f2cef'
     * ORDER BY org_id ASC
     * LIMIT 10;
     *
     * @param SecurityUser $user
     * @return $this
     * @todo Cache
     */
    public function attachOrganizationsToUser(SecurityUser $user)
    {
        if ($user->isSuper()) {
            return $this;
        }

        $select = new Select();
        $select->columns([
            new Expression('DISTINCT(o.org_id) AS org_id'),
            'title AS title',
            'type AS type',
            'description AS description'
        ]);

        $where = new Where();
        $where->addPredicate(new Operator('ug.user_id', Operator::OP_EQ, $user->getUserId()));

        $select->from(['o'  => 'organizations']);
        $select->join(['g'  => 'groups'], 'o.org_id = g.organization_id', [], Select::JOIN_LEFT);
        $select->join(['ug' => 'user_groups'], 'ug.group_id = g.group_id', [], Select::JOIN_LEFT);
        $select->where($where);
        $select->order('org_id ASC');
        $select->limit(10);

        $sql  = new Sql($this->adapter);
        $stmt = $sql->prepareStatementForSqlObject($select);
        $orgs = $stmt->execute();

        foreach ($orgs as $orgData) {
            $user->addOrganization(new Organization($orgData));
        }

        return $this;
    }

    /**
     * Attaches the group types to a logged in user
     *
     * SELECT
     * DISTINCT g.type
     * FROM groups g
     *   LEFT JOIN user_groups ug ON ug.group_id = g.group_id
     * WHERE ug.user_id = 'b4e9147a-e60a-11e5-b8ea-0800274f2cef'
     *   AND g.organization_id IN ('6bb4900e-e605-11e5-8c29-0800274f2cef', '6bb41c82-e605-11e5-a7a7-0800274f2cef');
     *
     * @param SecurityUser $user
     * @return $this
     * @todo Cache
     */
    public function attachGroupTypesToUser(SecurityUser $user)
    {
        if ($user->isSuper()) {
            return $this;
        }

        $orgIds = array_keys($user->getOrganizations());
        $select = new Select();
        $select->columns([
            new Expression('DISTINCT(g.type) AS type'),
        ]);

        $where = new Where();
        $where->addPredicate(new In('organization_id', $orgIds));

        $select->from(['g'  => 'groups']);
        $select->join(['ug' => 'user_groups'], 'ug.group_id = g.group_id', [], Select::JOIN_LEFT);
        $select->where($where);
        $select->limit(10);

        $sql   = new Sql($this->adapter);
        $stmt  = $sql->prepareStatementForSqlObject($select);
        $types = $stmt->execute();

        foreach ($types as $groupType) {
            $user->addGroupType($groupType['type']);
        }

        return $this;
    }
}
