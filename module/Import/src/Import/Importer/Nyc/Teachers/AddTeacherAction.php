<?php

namespace Import\Importer\Nyc\Teachers;

use Import\ActionInterface;
use User\Adult;
use User\Service\UserServiceInterface;

/**
 * Class AddTeacherAction
 *
 * ${CARET}
 */
class AddTeacherAction implements ActionInterface
{
    /**
     * @var UserServiceInterface
     */
    protected $userService;

    /**
     * @var Teacher
     */
    protected $teacher;

    /**
     * AddTeacherAction constructor.
     *
     * @param UserServiceInterface $userService
     * @param Teacher $teacher
     */
    public function __construct(UserServiceInterface $userService, Teacher $teacher)
    {
        $this->userService = $userService;
        $this->teacher     = $teacher;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return sprintf(
            'Creating a user for %s %s %s %s',
            $this->teacher->getRole(),
            $this->teacher->getFirstName(),
            $this->teacher->getLastName(),
            $this->teacher->getEmail()
        );
    }

    /**
     * Process the action
     *
     * @return void
     */
    public function execute()
    {
        $user = new Adult();
        $user->setFirstName($this->teacher->getFirstName());
        $user->setMiddleName($this->teacher->getMiddleName());
        $user->setLastName($this->teacher->getLastName());
        $user->setGender($this->teacher->getGender());
        $user->setUserName($this->teacher->getEmail());
        $user->setEmail($this->teacher->getEmail());

        $this->userService->createUser($user);
        $this->teacher->setUser($user);
    }

    /**
     * The priority that the action should be processed in
     *
     * @return int
     */
    public function priority()
    {
        return 50;
    }
}
