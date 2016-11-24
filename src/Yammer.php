<?php

namespace UPro\OAuth2\Client\Provider;

use UPro\OAuth2\Client\Provider\Exception\YammerIdentityProviderException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Yammer extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * @var string Key used in a token response to identify the resource owner.
     */
    const ACCESS_TOKEN_RESOURCE_OWNER_ID = 'user.id';

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://www.yammer.com/oauth2/authorize';
    }

    /**
     * Get access token url to retrieve token
     *
     * @param  array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://www.yammer.com/oauth2/access_token';
    }

    /**
     * Returns the URL for requesting the resource owner's details.
     *
     * @param AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://www.yammer.com/api/v1/users/'.$token->getResourceOwnerId().'.json';
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [];
    }

    /**
     * Check a provider response for errors.
     *
     * @link https://developer.yammer.com/docs/oauth-2
     *
     * @throws YammerIdentityProviderException
     *
     * @param  ResponseInterface $response
     * @param  string $data Parsed response data
     *
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        // If there is an issue authenticating the app, the authorization server
        // will issue an HTTP 400 and return the error in the body of the response.
        if ($response->getStatusCode() >= 400) {
            throw YammerIdentityProviderException::clientException($response, $data);
        }
    }

    /**
     * Prepares an parsed access token response for a grant.
     *
     * Custom mapping of expiration, etc should be done here. Always call the
     * parent method when overloading this method.
     *
     * @param  mixed $result
     *
     * @return array
     */
    protected function prepareAccessTokenResponse(array $result)
    {
        $result = parent::prepareAccessTokenResponse($result);
        $response = !empty($result['access_token']) ? $result['access_token'] : [];

        if (!empty($result['resource_owner_id'])) {
            $response['resource_owner_id'] = $result['resource_owner_id'];
        }

        if (!empty($response['token'])) {
            $response['access_token'] = $response['token'];
        }

        if (!empty($response['expires_at'])) {
            $response['expires'] = strtotime($response['expires_at']);
        }

        return $response;
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     *
     * @return YammerResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new YammerResourceOwner($response);
    }
}
