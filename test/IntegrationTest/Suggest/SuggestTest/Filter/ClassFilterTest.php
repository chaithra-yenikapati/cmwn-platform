<?php

namespace SuggestTest\Filter;

use IntegrationTest\AbstractDbTestCase as TestCase;
use IntegrationTest\DataSets\ArrayDataSet;
use IntegrationTest\TestHelper;
use Suggest\Filter\ClassFilter;
use User\Service\UserServiceInterface;
use User\UserInterface;

/**
 * Class ClassFilterTest
 * @package SuggestTest\Filter
 */
class ClassFilterTest extends TestCase
{
    /**
     * @var ClassFilter
     */
    protected $classFilter;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * @before
     */
    public function setUpClassFilter()
    {
        $this->classFilter = TestHelper::getServiceManager()->get(ClassFilter::class);
    }

    /**
     * @before
     */
    public function setUpUser()
    {
        $userService = TestHelper::getServiceManager()->get(UserServiceInterface::class);
        $this->user = $userService->fetchUser('english_student');
    }

    /**
     * @return ArrayDataSet
     */
    public function getDataSet()
    {
        $data = include __DIR__ . '/../../../DataSets/friends.dataset.php';
        return new ArrayDataSet($data);
    }

    /**
     * @test
     */
    public function testItShouldSuggestAllTheUsersInTheClass()
    {
        $container = $this->classFilter->getSuggestions($this->user);
        $this->assertTrue($container->offsetExists('math_student'));
        $this->assertTrue($container->offsetExists('english_student'));
        $this->assertTrue($container->offsetExists('other_student'));
        $this->assertTrue($container->offsetExists('english_teacher'));
    }
}
