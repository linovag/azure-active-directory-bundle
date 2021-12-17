<?php

namespace OpcodingAADBundle\Security;

use Exception;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use OpcodingAADBundle\Model\User;

/**
 * Class AzureUserProvider
 * @package OpcodingAADBundle\Security
 */
class AzureUserProvider implements UserProviderInterface
{
    public function __construct()
    {
    }

    /**
     * @throws Exception
     */
    public function loadUserByUsername($username): User
    {
        return (new User())->setUsername($username);
    }

    /**
     * @throws Exception
     */
    public function refreshUser(UserInterface $user): User
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class): bool
    {
        return $class === 'OpcodingAADBundle\Model\User';
    }
}
