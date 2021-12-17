<?php

namespace OpcodingAADBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package OpcodingAADBundle\Entity
 */
class User implements UserInterface
{
    /**
     * @var string
     */
    protected string $email;

    /**
     * @var string
     */
    protected string $fullName;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->email = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     *
     * @return $this
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return array('ROLE_USER');
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return null
     */
    public function eraseCredentials()
    {
        return null;
    }
}
