<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Security\Guard;

use App\Application\Event\SessionMessageEvent;
use App\Application\Security\Guard\Interfaces\LoginFormAuthenticatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

/**
 * Class LoginFormAuthenticator.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class LoginFormAuthenticator extends AbstractFormLoginAuthenticator implements LoginFormAuthenticatorInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        UrlGeneratorInterface $urlGenerator,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->urlGenerator = $urlGenerator;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('web_login');
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        if ($request->getPathInfo() !== $this->urlGenerator->generate('web_login') || 'POST' !== $request->getMethod()) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        if (!$request->request->get('login')['username'] || !$request->request->get('login')['password']) {
            return null;
        }

        return [
            'username' => $request->request->get('login')['username'],
            'password' => $request->request->get('login')['password']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->userPasswordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->urlGenerator->generate('dashboard_home'));
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->eventDispatcher->dispatch(
            SessionMessageEvent::NAME,
            new SessionMessageEvent('failure', 'security.login_failure')
        );

        return new RedirectResponse($this->urlGenerator->generate('web_login'));
    }
}
