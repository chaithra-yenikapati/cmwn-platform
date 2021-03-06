<?php

namespace IntegrationTest\Api\V1\Rest;

use Application\Exception\NotFoundException;
use IntegrationTest\AbstractApigilityTestCase as TestCase;
use IntegrationTest\TestHelper;
use Group\Service\GroupServiceInterface;
use Zend\Json\Json;

/**
 * Test GroupResourceTest
 *
 * @group DB
 * @group Group
 * @group GroupService
 * @group Api
 * @group Integration
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class GroupResourceTest extends TestCase
{
    /**
     * @test
     * @ticket core-864
     * @var GroupServiceInterface
     */
    protected $groupService;

    /**
     * @return \PHPUnit\DbUnit\DataSet\ArrayDataSet
     */
    public function getDataSet()
    {
        return $this->createArrayDataSet(include __DIR__ . '/../../../DataSets/group.dataset.php');
    }

    /**
     * @before
     */
    public function setUpUserService()
    {
        $this->groupService = TestHelper::getServiceManager()->get(GroupServiceInterface::class);
    }

    /**
     * @test
     * @ticket CORE-993
     */
    public function testItShouldAllowPrincipalToAccessGroup()
    {
        $this->logInUser('principal');
        $this->injectValidCsrfToken();
        $this->dispatch('/group/english');

        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
    }

    /**
     * @test
     *
     * @param string $user
     * @param string $url
     * @param string $method
     * @param array $params
     *
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
    public function testToCheckIfUserIsLoggedInToAccessGroups()
    {
        $this->injectValidCsrfToken();

        $this->dispatch('/group');
        $this->assertResponseStatusCode(401);
    }

    /**
     * @test
     */
    public function testToCheckIfUserIsLoggedInToAccessAParticularGroup()
    {
        $this->injectValidCsrfToken();

        $this->dispatch('/group/school');
        $this->assertResponseStatusCode(401);
    }

    /**
     * @test
     */
    public function testItShouldCheckCsrfToReturnValidGroups()
    {
        $this->logInUser('english_student');

        $this->dispatch('/group');
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(500);
    }

    /**
     * @test
     * @ticket CORE-864
     */
    public function testItShouldReturnValidGroups()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('english_student');

        $this->dispatch('/group');
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(200);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('_embedded', $body);
        $this->assertArrayHasKey('group', $body['_embedded']);
        $groups      = $body['_embedded']['group'];
        $expectedIds = ['english', 'school'];
        $actualIds   = [];
        foreach ($groups as $group) {
            $this->assertArrayHasKey('group_id', $group);
            $actualIds[] = $group['group_id'];
        }
        $this->assertEquals($actualIds, $expectedIds);
    }

    /**
     * @test
     * @ticket CORE-1060
     */
    public function testItShouldReturnValidGroupsForPrincipal()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('principal');
        $this->dispatch('/group');
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(200);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('_embedded', $body);
        $this->assertArrayHasKey('group', $body['_embedded']);
        $groups      = $body['_embedded']['group'];
        $expectedIds = ['english', 'school', 'math'];
        $actualIds   = [];
        foreach ($groups as $group) {
            $this->assertArrayHasKey('group_id', $group);
            $actualIds[] = $group['group_id'];
        }
        $this->assertEquals($actualIds, $expectedIds);
    }

    /**
     * @test
     */
    public function testItShouldCheckCsrfToReturnSchoolForUser()
    {
        $this->logInUser('english_student');

        $this->dispatch('/group?type=school');
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(500);
    }

    /**
     * @test
     * @ticket       CORE-864
     * @ticket       CORE-725
     * @dataProvider schoolUserDataProvider
     */
    public function testItShouldReturnSchoolForUser($login)
    {
        $this->injectValidCsrfToken();
        $this->logInUser($login);

        $this->dispatch('/group?type=school');
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(200);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('_embedded', $body);
        $this->assertArrayHasKey('group', $body['_embedded']);
        $groups      = $body['_embedded']['group'];
        $expectedIds = ['school'];
        $actualIds   = [];
        foreach ($groups as $group) {
            $this->assertArrayHasKey('group_id', $group);
            $actualIds[] = $group['group_id'];
        }
        $this->assertEquals($actualIds, $expectedIds);
    }

    /**
     * @test
     */
    public function testItShouldCheckCsrfToReturnGroupData()
    {
        $this->logInUser('english_student');

        $this->dispatch('/group/school');
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(500);
    }

    /**
     * @test
     * @dataProvider adultDataProvider
     */
    public function testItShouldReturnGroupDataForAdults($adult)
    {
        $this->injectValidCsrfToken();
        $this->logInUser($adult);

        $this->dispatch('/group/school');
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(200);
        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('group_id', $body);
        $this->assertArrayHasKey('organization_id', $body);
        $this->assertArrayHasKey('title', $body);
        $this->assertEquals('school', $body['group_id']);
        $this->assertEquals('district', $body['organization_id']);
        $this->assertEquals('Gina\'s School', $body['title']);

        $this->assertArrayHasKey('_links', $body);
        $this->assertArrayHasKey('group_reset', $body['_links']);
    }

    /**
     * @test
     * @dataProvider childDataProvider
     * @ticket CORE-2191
     */
    public function testItShouldReturnGroupDataForChildren($child)
    {
        $this->injectValidCsrfToken();
        $this->logInUser($child);

        $this->dispatch('/group/school');
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(200);
        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('group_id', $body);
        $this->assertArrayHasKey('organization_id', $body);
        $this->assertArrayHasKey('title', $body);
        $this->assertEquals('school', $body['group_id']);
        $this->assertEquals('district', $body['organization_id']);
        $this->assertEquals('Gina\'s School', $body['title']);

        $this->assertArrayHasKey('_links', $body);
        $this->assertArrayNotHasKey('group_reset', $body['_links']);
    }

    /**
     * @test
     */
    public function testItShould403WhenGroupWhichUserIsNotInIsAccessed()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('english_student');

        $this->dispatch('/group/other_math');
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(403);
    }

    /**
     * @test
     * @dataProvider userDataProvider
     */
    public function testItShouldCheckWhenInvalidGroupIsAccessed($user, $code)
    {
        $this->injectValidCsrfToken();
        $this->logInUser($user);

        $this->dispatch('/group/foobar');
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode($code);
    }

    /**
     * @test
     */
    public function testItShouldCheckCsrfToCreateGroup()
    {
        $this->logInUser('super_user');

        $postData = [
            'organization_id' => 'district',
            'title'           => 'Joni School',
            'description'     => 'this is new school',
            'type'            => 'school',
            'meta'            => null,
        ];
        $this->dispatch('/group', 'POST', $postData);
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(500);
    }

    /**
     * @test
     */
    public function testItShouldCreateGroup()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('super_user');

        $postData = [
            'organization_id' => 'district',
            'title'           => 'Joni School',
            'description'     => 'this is new school',
            'type'            => 'school',
            'meta'            => ['code' => 'test'],
        ];
        $this->dispatch('/group', 'POST', $postData);
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(201);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('group_id', $body);
        $newGroup = $this->groupService->fetchGroup($body['group_id'])->getArrayCopy();
        $this->assertEquals('district', $newGroup['organization_id']);
        $this->assertEquals('Joni School', $newGroup['title']);
        $this->assertEquals('this is new school', $newGroup['description']);
        $this->assertEquals(['code' => 'test'], $newGroup['meta']);
    }

    /**
     * @test
     * @ticket GAME-932
     */
    public function testItNotShouldCreateGroupWhenTheTypeIsMissing()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('super_user');

        $postData = [
            'organization_id' => 'district',
            'title'           => 'Joni School',
            'description'     => 'this is new school',
            'meta'            => ['code' => 'test'],
        ];
        $this->dispatch('/group', 'POST', $postData);
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(422);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertEquals(
            [
                'validation_messages' => [
                    'type' => [
                        0 => 'Invalid group type',
                    ],
                ],
                'type'                => 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                'title'               => 'Unprocessable Entity',
                'status'              => 422,
                'detail'              => 'Failed Validation',
            ],
            $body,
            'POST to group/ failed to return correct error messages with invalid type'
        );
    }

    /**
     * @test
     */
    public function testItShouldNotCreateGroupWithInvalidData()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('super_user');

        $this->dispatch('/group', 'POST', []);
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(422);
        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertEquals(
            [
                'validation_messages' => [
                    'type'            => [
                        0 => 'Invalid group type',
                    ],
                    'organization_id' => [
                        0 => 'Invalid Organization or not found'
                    ],
                    'title'           => [
                        0 => 'Invalid Title'
                    ],
                    'description'     => [
                        0 => 'Invalid Description'
                    ],
                ],
                'type'                => 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
                'title'               => 'Unprocessable Entity',
                'status'              => 422,
                'detail'              => 'Failed Validation',
            ],
            $body,
            'POST to group/ failed to return correct error messages with empty data'
        );
    }

    /**
     * @test
     */
    public function testItShouldNotAllowOthersToCreateGroup()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('english_student');

        $postData = [
            'organization_id' => 'district',
            'title'           => 'Joni School',
            'description'     => 'this is new school',
            'type'            => 'school',
            'meta'            => ['code' => 'test'],
        ];
        $this->dispatch('/group', 'POST', $postData);
        $this->assertResponseStatusCode(403);
    }

    /**
     * @test
     */
    public function testItShouldCheckCsrfToDeleteGroup()
    {
        $this->logInUser('super_user');

        $this->dispatch('/group/school', 'DELETE');
        $this->assertResponseStatusCode(500);
    }

    /**
     * @test
     */
    public function testItShouldDeleteGroup()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('super_user');

        $this->dispatch('/group/school', 'DELETE');
        $this->assertResponseStatusCode(204);
        $this->expectException(NotFoundException::class);
        $this->groupService->fetchGroup('school')->getArrayCopy();
    }

    /**
     * @test
     */
    public function testItShouldNotAllowOthersToDeleteGroup()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('english_teacher');

        $this->dispatch('/group/school', 'DELETE');
        $this->assertResponseStatusCode(403);
    }

    /**
     * @test
     */
    public function testItShouldCheckCsrfToUpdateGroup()
    {
        $this->logInUser('super_user');

        $putData = [
            'organization_id' => 'district',
            'title'           => 'Joni School',
            'description'     => 'this is new school',
            'type'            => 'school',
            'meta'            => null,
        ];
        $this->dispatch('/group/school', 'PUT', $putData);
        $this->assertResponseStatusCode(500);
    }

    /**
     * @test
     */
    public function testItShouldUpdateGroup()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('super_user');

        $putData = [
            'organization_id' => 'district',
            'title'           => 'Joni School',
            'description'     => 'this is new school',
            'type'            => 'school',
            'meta'            => null,
        ];
        $this->dispatch('/group/school', 'PUT', $putData);
        $this->assertResponseStatusCode(200);
        $group = $this->groupService->fetchGroup('school')->getArrayCopy();
        $this->assertEquals('district', $group['organization_id']);
        $this->assertEquals('Joni School', $group['title']);
        $this->assertEquals('this is new school', $group['description']);
        $this->assertEquals([], $group['meta']);
    }

    /**
     * @test
     */
    public function testItShouldNotAllowStudentToUpdateGroup()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('english_student');

        $putData = [
            'organization_id' => 'district',
            'title'           => 'Joni School',
            'description'     => 'this is new school',
            'type'            => 'school',
            'meta'            => null,
        ];
        $this->dispatch('/group/school', 'PUT', $putData);
        $this->assertResponseStatusCode(403);
    }

    /**
     * @test
     * @test
     * @ticket CORE-1061
     */
    public function testItShouldNotLetPrincipalUpdateOtherGroup()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('principal');

        $putData = [
            'organization_id' => 'district',
            'title'           => 'Joni School',
            'description'     => 'this is new school',
            'meta'            => null,
        ];
        $this->dispatch('/group/other_school', 'PUT', $putData);
        $this->assertResponseStatusCode(403);
    }

    /**
     * @test
     * @param $user
     * @param $expectedGroupIds
     * @ticket CORE-1062
     * @ticket CORE-1124
     * @ticket CORE-2378
     * @dataProvider childGroupDataProvider
     */
    public function testItShouldFetchChildGroups($user, $parent, $expectedGroupIds)
    {
        $this->injectValidCsrfToken();
        $this->logInUser($user);

        $this->dispatch('/group?type=class&parent=' . $parent);
        $this->assertMatchedRouteName('api.rest.group');
        $this->assertControllerName('api\v1\rest\group\controller');
        $this->assertResponseStatusCode(200);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('_embedded', $body);

        $groupList = $body['_embedded'];
        $this->assertArrayHasKey('group', $groupList);

        $actualGroupIds   = [];

        foreach ($groupList['group'] as $groupData) {
            $this->assertArrayHasKey('group_id', $groupData);
            array_push($actualGroupIds, $groupData['group_id']);
        }

        $this->assertEquals($expectedGroupIds, $actualGroupIds);
    }

    /**
     * @test
     * @ticket CORE-2331
     * @group MissingApiRoute
     */
    public function testItShouldFetchGroupByExternalId()
    {
        $this->markTestIncomplete("Add api route to fetch group by external id");
        $this->injectValidCsrfToken();
        $this->logInUser('super_user');
        $this->dispatch('/group?externalId=foo&networkId=bar');
    }

    /**
     * @test
     * @ticket CORE-2331
     * @group MissingApiRoute
     */
    public function testItShouldAddChildToGroup()
    {
        $this->markTestIncomplete("Create an API route for adding child group to parent");
        $this->injectValidCsrfToken();
        $this->logInUser('super_user');
        $postData = [
            'organization_id' => 'district',
            'title'           => 'Joni School',
            'description'     => 'this is new school',
            'type'            => 'school',
            'meta'            => ['code' => 'test'],
            'parent_id'       => 'parent\'s user_id'
        ];
        $this->dispatch('/group', 'POST', $postData);
    }

    /**
     * @test
     * @dataProvider halLinkDataProvider
     * @group HAL
     */
    public function testItShouldAddCorrectHalLinksOnGroupEntities($login, $group, $expected)
    {
        $this->injectValidCsrfToken();
        $this->logInUser($login);
        $this->dispatch('/group/' . $group);
        $this->assertResponseStatusCode(200);
        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('_links', $body);
        $links = $body['_links'];

        $actual = [];
        foreach ($links as $label => $link) {
            $actual[] = $label;
        }

        sort($expected);
        sort($actual);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function testItShouldLoadGroupsForPageTwoAndBuildCorrectFindLink()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('super_user');
        $this->dispatch('/group?page=2&per_page=1');
        $this->assertResponseStatusCode(200);
        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('_links', $body);
        $links = $body['_links'] ?? [];

        $this->assertArrayHasKey('find', $links);

        $this->assertEquals(
            ['href' => 'http://api.test.com/group?per_page=1{&page}', 'templated' => true],
            $links['find'],
            'Find link was incorrectly built for group endpoint'
        );
    }

    /**
     * @return array
     */
    public function changePasswordDataProvider()
    {
        return [
            0 => [
                'english_student',
                '/group',
            ],
            1 => [
                'super_user',
                '/group',
                'POST',
                [
                    'organization_id' => 'district',
                    'title'           => 'Joni School',
                    'description'     => 'this is new school',
                    'type'            => 'school',
                    'meta'            => null,
                ],
            ],
            2 => [
                'super_user',
                '/group/school',
                'PUT',
                [
                    'organization_id' => 'district',
                    'title'           => 'Joni School',
                    'description'     => 'this is new school',
                    'type'            => 'school',
                    'meta'            => null,
                ],
            ],
            3 => [
                'super_user',
                '/group/school',
                'DELETE',
            ],
            4 => [
                'english_student',
                '/group/school',
            ],
        ];
    }

    /**
     * @return array
     */
    public function schoolUserDataProvider()
    {
        return [
            'English Teacher' => [
                'english_teacher',
            ],
            'English Student' => [
                'english_student',
            ],
        ];
    }

    /**
     * @return array
     */
    public function userDataProvider()
    {
        return [
            'English student' => [
                'english_student',
                403,
            ],
            'English Teacher' => [
                'english_teacher',
                403,
            ],
            'Super User' => [
                'super_user',
                404,
            ],
        ];
    }

    /**
     * @return array
     */
    public function childDataProvider()
    {
        return [
            'English Student' => [
                'english_student',
            ],
            'Math Student'    => [
                'math_student',
            ],
        ];
    }

    /**
     * @return array
     */
    public function adultDataProvider()
    {
        return [
            'English Teacher' => [
                'english_teacher',
            ],
            'Math Teacher'    => [
                'math_teacher',
            ],
            'Principal'       => [
                'principal',
            ],
            'Super'           => [
                'super_user',
            ],
        ];
    }

    /**
     * @return array
     */
    public function childGroupDataProvider()
    {
        return [
            'English Student' => [
                'english_student',
                'school',
                ['english']
            ],
            'Super User for Math'    => [
                'super_user',
                'school',
                ['english', 'math']
            ],
            'Super User for Other Math'    => [
                'super_user',
                'other_school',
                ['other_math']
            ],
            'English Teacher'    => [
                'english_teacher',
                'school',
                ['english']
            ],
            'Principal'    => [
                'principal',
                'school',
                ['english', 'math']
            ],
        ];
    }

    /**
     * @return array
     */
    public function halLinkDataProvider()
    {
        return [
            'Super User for School' => [
                'super_user',
                'school',
                [
                    0 => 'self',
                    1 => 'group_users',
                    2 => 'group_reset',
                    3 => 'group_class',
                    4 => 'import',
                    5 => 'group_address',
                ]
            ],
            'Super User for Class' => [
                'super_user',
                'english',
                [
                    0 => 'self',
                    1 => 'group_users',
                    2 => 'group_reset',
                    3 => 'import',
                    4 => 'group_address',
                ]
            ],
            'Principal for School' => [
                'principal',
                'school',
                [
                    0 => 'self',
                    1 => 'group_users',
                    2 => 'group_reset',
                    3 => 'group_class',
                    4 => 'import',
                    5 => 'group_address',
                ]
            ],
            'Principal for English' => [
                'principal',
                'english',
                [
                    0 => 'self',
                    1 => 'group_users',
                    2 => 'group_reset',
                    3 => 'import',
                    4 => 'group_address',
                ]
            ],
            'English Teacher for school' => [
                'english_teacher',
                'school',
                [
                    0 => 'self',
                    1 => 'group_users',
                    2 => 'group_reset',
                    3 => 'group_class',
                    4 => 'group_address',
                ]
            ],
            'English Teacher for English' => [
                'english_teacher',
                'english',
                [
                    0 => 'self',
                    1 => 'group_users',
                    2 => 'group_reset',
                    3 => 'group_address',
                ]
            ],
            'English Student for School' => [
                'english_student',
                'school',
                [
                    0 => 'self',
                    1 => 'group_users',
                    2 => 'group_class',
                    3 => 'group_address',
                ]
            ],
            'English Student for class' => [
                'english_student',
                'english',
                [
                    0 => 'self',
                    1 => 'group_users',
                    2 => 'group_address',
                ]
            ],
        ];
    }
}
