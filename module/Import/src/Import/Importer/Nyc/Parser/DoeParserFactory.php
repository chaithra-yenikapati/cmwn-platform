<?php

namespace Import\Importer\Nyc\Parser;

use Group\Service\GroupServiceInterface;
use Group\Service\UserGroupServiceInterface;
use Import\Importer\Nyc\ClassRoom\ClassRoomRegistry;
use Import\Importer\Nyc\Students\StudentRegistry;
use Import\Importer\Nyc\Teachers\TeacherRegistry;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DoeParserFactory
 *
 * ${CARET}
 */
class DoeParserFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ClassRoomRegistry $classRegistry */
        $classRegistry    = $serviceLocator->get('Nyc\ClassRegistry');

        /** @var TeacherRegistry $teacherRegistry */
        $teacherRegistry  = $serviceLocator->get('Nyc\TeacherRegistry');

        /** @var StudentRegistry $studentRegistry */
        $studentRegistry  = $serviceLocator->get('Nyc\StudentRegistry');

        /** @var UserGroupServiceInterface $userGroupService */
        $userGroupService = $serviceLocator->get('Group\Service\UserGroupService');

        /** @var GroupServiceInterface $groupService */
        $groupService     = $serviceLocator->get('Group\Service\GroupService');

        return new DoeParser(
            $classRegistry,
            $teacherRegistry,
            $studentRegistry,
            $userGroupService,
            $groupService
        );
    }

}
