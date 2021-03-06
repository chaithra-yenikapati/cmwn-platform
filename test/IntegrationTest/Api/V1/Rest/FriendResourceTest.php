<?php

namespace IntegrationTest\Api\V1\Rest;

use Friend\FriendInterface;
use Friend\Service\FriendServiceInterface;
use IntegrationTest\AbstractApigilityTestCase as TestCase;
use IntegrationTest\TestHelper;
use User\Child;
use User\UserInterface;
use Zend\Json\Json;
use IntegrationTest\DataSets\ArrayDataSet;

/**
 * Test FriendResourceTest
 *
 * @group Friend
 * @group IntegrationTest
 * @group FriendService
 * @group DB
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class FriendResourceTest extends TestCase
{
    /**
     * @var FriendServiceInterface
     */
    protected $friendService;

    /**
     * @var UserInterface|Child
     */
    protected $user;

    /**
     * @var UserInterface|Child
     */
    protected $friend;

    /**
     * @return ArrayDataSet
     */
    public function getDataSet()
    {
        return $this->createArrayDataSet(include __DIR__ . '/../../../DataSets/friends.dataset.php');
    }

    /**
     * @before
     */
    public function setUpFriendService()
    {
        $this->friendService = TestHelper::getServiceManager()->get(FriendServiceInterface::class);
    }

    /**
     * @before
     */
    public function setUpUser()
    {
        $this->user = new Child(['user_id' => 'english_student']);
    }

    /**
     * @before
     */
    public function setUpFriend()
    {
        $this->friend = new Child(['user_id' => 'math_student']);
    }

    /**
     * @test
     * @param string $user
     * @param string $url
     * @param string $method
     * @param array $params
     * @dataProvider changePasswordDataProvider
     */
    public function testItShouldCheckChangePasswordException($user, $url, $method = 'GET', $params = [])
    {
        $this->injectValidCsrfToken();
        $this->logInChangePasswordUser($user);
        $this->assertChangePasswordException($url, $method, $params);
    }

    /**
     * @test
     */
    public function testItShouldReturnCorrectFriendListForUser()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('english_student');
        $this->dispatch('/user/english_student/friend');
        $this->assertResponseStatusCode(200);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);

        $this->assertArrayHasKey('_embedded', $body);
        $this->assertArrayHasKey('friend', $body['_embedded']);

        $friendList = $body['_embedded']['friend'];
        $actualId   = [];
        foreach ($friendList as $friend) {
            $this->assertArrayHasKey('friend_id', $friend);
            array_push($actualId, $friend['friend_id']);
        }

        $this->assertEquals(
            ['english_student_1'],
            $actualId,
            'Service did not return correct friends'
        );
    }

    /**
     * @test
     */
    public function testItShouldReturnCorrectFriendListForFriend()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('math_student');
        $this->dispatch('/user/english_student/friend');
        $this->assertResponseStatusCode(200);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);

        $this->assertArrayHasKey('_embedded', $body);
        $this->assertArrayHasKey('friend', $body['_embedded']);

        $friendList = $body['_embedded']['friend'];
        $actualId   = [];
        foreach ($friendList as $friend) {
            $this->assertArrayHasKey('friend_id', $friend);
            array_push($actualId, $friend['friend_id']);
        }

        $this->assertEquals(
            ['english_student_1'],
            $actualId,
            'Service did not return correct friends'
        );
    }

    /**
     * @test
     */
    public function testItShouldAttachFriendAsPending()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('english_student');
        $this->dispatch('/user/english_student/friend', 'POST', ['friend_id' => $this->friend->getUserId()]);
        $this->assertResponseStatusCode(201);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('friend_id', $body);
        $this->assertArrayHasKey('friend_status', $body);
        $this->assertEquals($this->friend->getUserId(), $body['friend_id']);
        $this->assertEquals(FriendInterface::PENDING, $body['friend_status']);
    }

    /**
     * @test
     */
    public function testItShouldAttachFriendAsAccepted()
    {
        $this->friendService->attachFriendToUser($this->friend, $this->user);
        $this->injectValidCsrfToken();
        $this->logInUser('english_student');
        $this->dispatch('/user/english_student/friend', 'POST', ['friend_id' => $this->friend->getUserId()]);
        $this->assertResponseStatusCode(201);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('friend_id', $body);
        $this->assertArrayHasKey('friend_status', $body);
        $this->assertEquals($this->friend->getUserId(), $body['friend_id']);
        $this->assertEquals(FriendInterface::FRIEND, $body['friend_status']);
    }

    /**
     * @test
     */
    public function testItShouldNotAllowAdultToFriendChild()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('english_teacher');
        $this->dispatch('/user/english_teacher/friend', 'POST', ['friend_id' => $this->friend->getUserId()]);
        $this->assertResponseStatusCode(403);
    }

    /**
     * @test
     */
    public function testItShouldNotAllowChildToFriendAdult()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('english_student');
        $this->dispatch('/user/english_student/friend', 'POST', ['friend_id' => 'english_teacher']);
        $this->assertResponseStatusCode(422);
    }

    /**
     * @test
     */
    public function testItShouldNotAllowAdultToFriendAdult()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('math_teacher');
        $this->dispatch('/user/math_teacher/friend', 'POST', ['friend_id' => 'english_teacher']);
        $this->assertResponseStatusCode(403);
    }

    /**
     * @test
     * @ticket CORE-558
     */
    public function testItShouldAllowChildToAccessFriendEndpoint()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('english_student');
        $this->dispatch('/user/english_student/friend');
        $this->assertResponseStatusCode(200);
    }

    /**
     * @return array
     */
    public function changePasswordDataProvider()
    {
        return [
            0 => [
                'english_student',
                '/user/english_student/friend'
            ],
            1 => [
                'english_student',
                '/user/english_student/friend',
                'POST',
                [
                    'friend_id' => 'math_student'
                ]
            ],
        ];
    }
}
