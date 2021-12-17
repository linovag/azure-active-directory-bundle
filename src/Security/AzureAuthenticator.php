<?php

namespace OpcodingAADBundle\Security;

use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Token\AccessToken;
use OpcodingAADBundle\Model\User;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use TheNetworg\OAuth2\Client\Provider\AzureResourceOwner;

/**
 * Class AzureAuthenticator
 *
 * @package App\Security
 */
class AzureAuthenticator extends SocialAuthenticator
{
    /**
     * @var ClientRegistry
     */
    protected ClientRegistry $clientRegistry;

    /**
     * AzureAuthenticator constructor.
     *
     * @param ClientRegistry         $clientRegistry
     */
    public function __construct(ClientRegistry $clientRegistry)
    {
        $this->clientRegistry = $clientRegistry;
    }

    /**
     * Check if the request is supported by this listener class
     *
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->getPathInfo() === '/login-azure' && $request->isMethod('GET');
    }

    /**
     * Retrieve the credentials for the user
     *
     * @param Request $request
     * @return AccessToken
     */
    public function getCredentials(Request $request): AccessToken
    {
        return $this->fetchAccessToken($this->getAzureClient());
    }

    /**
     * Retrieve the user according to the token sent by the provider
     *
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        /** @var AzureResourceOwner $userInfo */
        $userInfo = $this
            ->getAzureClient()
            ->fetchUserFromToken($credentials)
        ;

        $email = $userInfo->claim('upn') ?: $userInfo->claim('unique_name');
        $name = $userInfo->claim('name');

        /** @var User $user */
        $user = $userProvider->loadUserByIdentifier($email);
        $user->setFullName($name);

        return $user;
    }

    /**
     * @return OAuth2ClientInterface
     */
    protected function getAzureClient(): OAuth2ClientInterface
    {
        return $this
            ->clientRegistry
            ->getClient('azure');
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse|Response
    {
        return new RedirectResponse('/login');
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        // on success, let the request continue
        return null;
    }
}
