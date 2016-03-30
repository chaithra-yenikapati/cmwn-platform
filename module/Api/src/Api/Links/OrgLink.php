<?php

namespace Api\Links;

use Org\OrganizationInterface;
use ZF\Hal\Link\Link;

/**
 * Class OrgLink
 * @package Api\Links
 */
class OrgLink extends Link
{
    /**
     * OrgLink constructor.
     * @param string $org
     */
    public function __construct($org)
    {
        $type = $org instanceof OrganizationInterface ? $org->getType() : $org;
        parent::__construct(strtolower('org_' . $type));
        $this->setRoute('api.rest.org', [], ['query' => ['type' => $type]]);
    }
}