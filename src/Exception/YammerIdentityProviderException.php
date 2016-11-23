<?php

namespace UPro\OAuth2\Client\Provider\Exception;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;

class YammerIdentityProviderException extends IdentityProviderException
{
    /**
     * Creates client exception from response.
     *
     * @param  ResponseInterface $response
     * @param  string $data Parsed response data
     *
     * @return YammerIdentityProviderException
     */
    public static function clientException(ResponseInterface $response, $data)
    {
        $message = $response->getReasonPhrase();

        if (isset($data['error']) && !empty($data['error']['message'])) {
            $message = $data['error']['type'].': '.$data['error']['message'];
        }

        return static::fromResponse($response, $message);
    }

    /**
     * Creates identity exception from response.
     *
     * @param  ResponseInterface $response
     * @param  string $message
     *
     * @return YammerIdentityProviderException
     */
    protected static function fromResponse(ResponseInterface $response, $message = null)
    {
        return new static($message, $response->getStatusCode(), (string)$response->getBody());
    }
}