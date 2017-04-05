<?php

namespace UPro\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class YammerResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array $response
     */
    public function __construct(array $response = [])
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'id');
    }

    /**
     * Return if resource owner is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->getValueByKey($this->response, 'state') == 'active';
    }

    /**
     * Get resource owner email
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getValueByKey($this->response, 'email');
    }

    /**
     * Get resource owner name (login)
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->getValueByKey($this->response, 'name');
    }

    /**
     * Get resource owner first name
     *
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->getValueByKey($this->response, 'first_name');
    }

    /**
     * Get resource owner last name
     *
     * @return string|null
     */
    public function getLastName()
    {
        return $this->getValueByKey($this->response, 'last_name');
    }

    /**
     * Get the resource owner profile photo.
     *
     * @return string|null
     */
    public function getPhotoUrl()
    {
        return $this->getValueByKey($this->response, 'mugshot_url');
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
