<?php

namespace UPro\OAuth2\Client\Test\Provider;

use Mockery as m;

class YammerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \UPro\OAuth2\Client\Provider\Yammer
     */
    protected $provider;

    protected function setUp()
    {
        $this->provider = new \UPro\OAuth2\Client\Provider\Yammer([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);
        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertNotNull($this->provider->getState());
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        $this->assertEquals('https', $uri['scheme']);
        $this->assertEquals('www.yammer.com', $uri['host']);
        $this->assertEquals('/oauth2/authorize', $uri['path']);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];
        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);
        $this->assertEquals('https', $uri['scheme']);
        $this->assertEquals('www.yammer.com', $uri['host']);
        $this->assertEquals('/oauth2/access_token', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $responseBody = '{"access_token": {
            "view_subscriptions": true,
            "expires_at": null,
            "authorized_at": "2011/04/06 16:25:46 +0000",
            "modify_subscriptions": true,
            "modify_messages": true,
            "network_permalink": "yammer-inc.com",
            "view_members": true,
            "view_tags": true,
            "network_id": 155465488,
            "user_id": 1014216,
            "view_groups": true,
            "token": "ajsdfiasd7f6asdf8o",
            "network_name": "Yammer",
            "view_messages": true,
            "created_at": "2011/04/06 16:25:46 +0000"
        }, "user": {"id": 1014216}}';

        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn($responseBody);
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'application/json']);
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $this->assertEquals('ajsdfiasd7f6asdf8o', $token->getToken());
        $this->assertEquals('1014216', $token->getResourceOwnerId());
        $this->assertNull($token->getExpires());
        $this->assertNull($token->getRefreshToken());
    }

    /**
     * @expectedException \UPro\OAuth2\Client\Provider\Exception\YammerIdentityProviderException
     **/
    public function testExceptionThrownWhenErrorObjectReceived()
    {
        $responseBody = '{"error":{"type": "OAuthException", "message": "Error validating verification code."}}';
        $status = rand(400, 600);
        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn($responseBody);
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'application/json']);
        $postResponse->shouldReceive('getStatusCode')->andReturn($status);
        $postResponse->shouldReceive('getReasonPhrase')->andReturn('Http Reason Phrase');
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(1)
            ->andReturn($postResponse);
        $this->provider->setHttpClient($client);
        $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }
}
