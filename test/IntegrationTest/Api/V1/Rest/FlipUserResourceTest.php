<?php

namespace IntegrationTest\Api\V1\Rest;

use IntegrationTest\AbstractApigilityTestCase as TestCase;
use Zend\Json\Json;
use IntegrationTest\DataSets\ArrayDataSet;

/**
 * Test FlipResourceTest
 *
 * @group DB
 * @group Flip
 * @group Resource
 * @group FlipUser
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class FlipUserResourceTest extends TestCase
{
    /**
     * @return ArrayDataSet
     */
    public function getDataSet()
    {
        return $this->createArrayDataSet(include __DIR__ . '/../../../DataSets/flip.dataset.php');
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
     * @dataProvider validUserDataProvider
     * @ticket       CORE-773
     */
    public function testItShouldCheckIfUserLoggedInCanSeeUserFlip($login)
    {
        $this->injectValidCsrfToken();
        $this->logInUser($login);

        $this->dispatch('/user/english_student/flip');
        $this->assertResponseStatusCode(200);
    }

    /**
     * @test
     * @ticket       CORE-773
     */
    public function testItShouldNotAllowYouToSeeFilpsForUserYouDoNotHaveAccessTo()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('math_teacher');

        $this->dispatch('/user/english_student/flip');
        $this->assertResponseStatusCode(403);
    }

    /**
     * @test
     * @dataProvider validUserDataProvider
     */
    public function testItShouldCheckIfRouteUrlIsCorrect($login)
    {
        $this->injectValidCsrfToken();
        $this->logInUser($login);

        $this->dispatch('/user/manchuck/flip');
        $this->assertResponseStatusCode(404);
    }

    /**
     * @test
     */
    public function testItShouldReturnValidUserFlips()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('math_student');

        $this->dispatch('/user/math_student/flip');
        $this->assertMatchedRouteName('api.rest.flip-user');
        $this->assertControllerName('api\v1\rest\flipuser\controller');
        $this->assertResponseStatusCode(200);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('_embedded', $body);
        $embedded = $body['_embedded'];
        $this->assertArrayHasKey('flip_user', $embedded);
        $flips = $embedded['flip_user'];
        $this->assertArrayHasKey('flip_id', $flips[0]);

        $expectedids = ['polar-bear', 'sea-turtle'];
        $actualids   = [];
        foreach ($flips as $flip) {
            $actualids[] = $flip['flip_id'];
        }
        $this->assertEquals($actualids, $expectedids);
    }

    /**
     * @test
     */
    public function testItShouldReturnFlipDataForUser()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('math_student');

        $this->dispatch('/user/math_student/flip/polar-bear');
        $this->assertMatchedRouteName('api.rest.flip-user');
        $this->assertControllerName('api\v1\rest\flipuser\controller');
        $this->assertResponseStatusCode(200);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('_embedded', $body);
        $this->assertArrayHasKey('items', $body['_embedded']);
        $embedded = $body['_embedded']['items'];

        $this->assertArrayHasKey('flip_id', $embedded[0]);
        $this->assertArrayHasKey('title', $embedded[0]);
        $this->assertArrayHasKey('description', $embedded[0]);
        $this->assertEquals($embedded[0]['flip_id'], "polar-bear");
        $this->assertEquals($embedded[0]['title'], "Polar Bear");
        $this->assertEquals(
            'The magnificent Polar Bear is in danger of becoming extinct.  ' .
            'Get the scoop and go offline for the science on how they stay warm!',
            $embedded[0]['description']
        );
    }

    /**
     * @test
     */
    public function testItShouldCreateValidFlipForUser()
    {
        $this->injectValidCsrfToken();
        $this->logInUser('math_student');

        $this->dispatch('/user/math_student/flip', 'POST', ['flip_id' => 'polar-bear']);
        $this->assertMatchedRouteName('api.rest.flip-user');
        $this->assertControllerName('api\v1\rest\flipuser\controller');
        $this->assertResponseStatusCode(201);

        $body = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
        $this->assertArrayHasKey('_embedded', $body);
        $this->assertArrayHasKey('items', $body['_embedded']);
        $embedded = $body['_embedded']['items'];

        $this->assertArrayHasKey('flip_id', $embedded[0]);
        $this->assertArrayHasKey('title', $embedded[0]);
        $this->assertArrayHasKey('description', $embedded[0]);
        $this->assertEquals($embedded[0]['flip_id'], "polar-bear");
        $this->assertEquals($embedded[0]['title'], "Polar Bear");
        $this->assertEquals(
            'The magnificent Polar Bear is in danger of becoming extinct.' .
            '  Get the scoop and go offline for the science on how they stay warm!',
            $embedded[0]['description']
        );
    }

    /**
     * @return array
     */
    public function validUserDataProvider()
    {
        return [
            'English Teacher' => [
                'english_teacher',
            ],
            'Principal'       => [
                'principal',
            ],
            'Math Student'    => [
                'math_student',
            ],
            'English Student' => [
                'english_student',
            ],
            'Super'           => [
                'super_user',
            ],
        ];
    }

    /**
     * @return array
     */
    public function changePasswordDataProvider()
    {
        return [
            0 => [
                'english_student',
                '/user/english_student/flip',
            ],
            1 => [
                'math_student',
                '/user/math_student/flip',
                'POST',
                [
                    'flip_id' => 'polar-bear',
                ],
            ],
        ];
    }
}
