<?php
namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use FOS\OAuthServerBundle\Model\ClientManager;

/**
 * Class NewsControllerTest
 * @package AppBundle\Tests\Controller
 */
class NewsControllerTest extends WebTestCase
{
    /** @var ClientManager */
    private $clientManager;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->clientManager = $kernel->getContainer()->get('fos_oauth_server.client_manager.default');
    }
    /**
     * @covers ::getAction
     */
    public function testGetNews_ShouldReturnUnauthorized()
    {
        $client = static::createClient();

        $client->request('GET', '/news/1');

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    /**
     * @covers ::getAction
     */
    public function testGetNews_WithAuthorization()
    {
        $oAuth2Client = $this->clientManager->findClientBy(['id'=>1]);
        $client = static::createClient();
        $client->request('POST', '/oauth/v2/token', [
            'grant_type' => 'password',
            'client_id' => $oAuth2Client->getPublicId(),
            'client_secret' => $oAuth2Client->getSecret(),
            'username' => 'test',
            'password' => 'test'
        ]);

        $token = json_decode($client->getResponse()->getContent(), true);

        $client->request('GET', '/news/1', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token['access_token']
        ]);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}