<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

class Consumer implements JsonSerializable
{
    /**
     * The consumer id
     *
     * @var integer
     */
    protected $id;

    /**
     * The consumer key
     *
     * @var string
     */
    protected $key;

    /**
     * The consumer name
     *
     * @var string
     */
    protected $name;

    /**
     * The consumer secret
     *
     * @var string
     */
    protected $secret;

    /**
     * The consumer status
     *
     * @var boolean
     */
    protected $isActive;

    /**
     * All tokens for the user.
     *
     * @var ArrayCollection
     */
    protected $tokens;

    public function __construct()
    {
        $this->id = null;
        $this->key = '';
        $this->name = '';
        $this->secret = '';

        $this->isActive = false;

        $this->tokens = new ArrayCollection;
    }

    /**
     * Set the consumer id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the consumer id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the consumer key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get the consumer key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the consumer name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the consumer name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the consumer secret
     *
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Get the consumer secret
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set the consumer status
     *
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Get the consumer status
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * Set the consumer tokens
     *
     * @param ArrayCollection $tokens
     */
    public function setTokens(ArrayCollection $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Get the consumer tokens
     *
     * @return ArrayCollection
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->getId(),

            'key' => $this->getKey(),
            'name' => $this->getName(),
            'secret' => $this->getSecret(),
            'isActive' => $this->isActive(),

            // 'tokens' => $this->getTokens() ? $this->getTokens()->getKeys() : []
        ];

        return $json;
    }
}
