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

namespace App\Tests\Application\Security\Guard;

use App\Application\Security\Guard\Interfaces\LoginFormAuthenticatorInterface;
use App\Application\Security\Guard\LoginFormAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\AuthenticatorInterface;

/**
 * Class LoginFormAuthenticatorUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class LoginFormAuthenticatorUnitTest extends TestCase
{
    /**
     * @var LoginFormAuthenticatorInterface|AuthenticatorInterface|null
     */
    private $authenticator = null;

    /**
     * @var EventDispatcherInterface|null
     */
    private $eventDispatcher = null;

    /**
     * @var Request|null
     */
    private $request = null;

    /**
     * @var UrlGeneratorInterface|null
     */
    private $urlGenerator = null;

    /**
     * @var UserPasswordEncoderInterface|null
     */
    private $userPasswordEncoder = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->userPasswordEncoder = $this->createMock(UserPasswordEncoderInterface::class);

        $this->authenticator = new LoginFormAuthenticator(
            $this->eventDispatcher,
            $this->urlGenerator,
            $this->userPasswordEncoder
        );
    }

    public function testItImplements()
    {
        static::assertInstanceOf(
            AbstractFormLoginAuthenticator::class,
            $this->authenticator
        );
        static::assertInstanceOf(
            LoginFormAuthenticatorInterface::class,
            $this->authenticator
        );
    }

    /**
     * @dataProvider provideWrongRequestPath
     *
     * @param string $requestPath
     * @param string $loginPath
     */
    public function testItRefuseToAuthenticateDueToBadRoute(string $requestPath, string $loginPath)
    {
        $this->urlGenerator->method('generate')->willReturn($loginPath);

        $this->request = Request::create($requestPath, 'POST');

        static::assertFalse($this->authenticator->supports($this->request));
    }

    /**
     * @dataProvider provideWrongMethod
     *
     * @param string $requestPath
     * @param string $loginPath
     * @param string $method
     */
    public function testItRefuseToAuthenticateDueToWrongMethod(string $requestPath, string $loginPath, string $method)
    {
        $this->urlGenerator->method('generate')->willReturn($loginPath);

        $this->request = Request::create($requestPath, $method);

        static::assertFalse($this->authenticator->supports($this->request));
    }

    /**
     * @dataProvider provideRightRequestPath
     *
     * @param string $requestPath
     * @param string $loginPath
     */
    public function testItAcceptToAuthenticate(string $requestPath, string $loginPath)
    {
        $this->urlGenerator->method('generate')->willReturn($loginPath);

        $this->request = Request::create($requestPath, 'POST');

        static::assertTrue($this->authenticator->supports($this->request));
    }

    /**
     * @dataProvider provideRightRequestPath
     *
     * @param string $requestPath
     * @param string $loginPath
     */
    public function testItRefuseToAuthenticateDueToMissingCredentials(string $requestPath, string $loginPath)
    {
        $this->urlGenerator->method('generate')->willReturn($loginPath);

        $this->request = Request::create($requestPath, 'POST', []);

        static::assertNull($this->authenticator->getCredentials($this->request));
    }

    /**
     * @dataProvider provideRightRequestPath
     *
     * @param string $requestPath
     * @param string $loginPath
     */
    public function testItAcceptToReturnCredentials(string $requestPath, string $loginPath)
    {
        $this->urlGenerator->method('generate')->willReturn($loginPath);

        $this->request = Request::create($requestPath, 'POST', ['login' => ['username' => 'toto', 'password' => 'toto']]);

        static::assertArrayHasKey('username', $this->authenticator->getCredentials($this->request));
        static::assertArrayHasKey('password', $this->authenticator->getCredentials($this->request));
    }

    /**
     * @dataProvider provideWrongCredentials
     *
     * @param string $requestPath
     * @param string $loginPath
     * @param array $credentials
     */
    public function testItDoesNotFindUser(string $requestPath, string $loginPath, array $credentials)
    {
        $userProviderMock = $this->createMock(UserProviderInterface::class);

        $userProviderMock->method('loadUserByUsername')->willReturn(null);
        $this->urlGenerator->method('generate')->willReturn($loginPath);

        $this->request = Request::create($requestPath, 'POST', ['login' => ['username' => 'toto', 'password' => 'toto']]);

        static::assertNull($this->authenticator->getUser($credentials, $userProviderMock));
    }

    /**
     * @dataProvider provideRightCredentials
     *
     * @param string $requestPath
     * @param string $loginPath
     * @param array $credentials
     */
    public function testItFindUser(string $requestPath, string $loginPath, array $credentials)
    {
        $userProviderMock = $this->createMock(UserProviderInterface::class);
        $userInterfaceMock = $this->createMock(UserInterface::class);

        $userProviderMock->method('loadUserByUsername')->willReturn($userInterfaceMock);
        $this->urlGenerator->method('generate')->willReturn($loginPath);

        $this->request = Request::create($requestPath, 'POST', ['login' => ['username' => 'toto', 'password' => 'toto']]);

        static::assertInstanceOf(UserInterface::class, $this->authenticator->getUser($credentials, $userProviderMock));
    }

    /**
     * @dataProvider provideEmptyCredentials
     *
     * @param array $credentials
     */
    public function testItRefuseCredentials(array $credentials)
    {
        $this->userPasswordEncoder->method('isPasswordValid')->willReturn(false);

        $userInterfaceMock = $this->createMock(UserInterface::class);

        static::assertFalse($this->authenticator->checkCredentials($credentials, $userInterfaceMock));
    }

    /**
     * @dataProvider provideEmptyCredentials
     *
     * @param array $credentials
     */
    public function testItAcceptCredentials(array $credentials)
    {
        $this->userPasswordEncoder->method('isPasswordValid')->willReturn(true);

        $userInterfaceMock = $this->createMock(UserInterface::class);

        static::assertTrue($this->authenticator->checkCredentials($credentials, $userInterfaceMock));
    }

    /**
     * @return \Generator
     */
    public function provideWrongRequestPath()
    {
        yield array('/fr/register', '/fr/login');
        yield array('/fr/', '/fr/login');
        yield array('/en/register', '/en/login');
        yield array('/en/', '/en/login');
    }

    /**
     * @return \Generator
     */
    public function provideWrongMethod()
    {
        yield array('/fr/register', '/fr/login', 'GET');
        yield array('/fr/', '/fr/login', 'GET');
        yield array('/en/register', '/en/login', 'GET');
        yield array('/en/', '/en/login', 'GET');
    }

    /**
     * @return \Generator
     */
    public function provideRightRequestPath()
    {
        yield array('/fr/login', '/fr/login');
        yield array('/fr/login', '/fr/login');
        yield array('/en/login', '/en/login');
        yield array('/en/login', '/en/login');
    }

    /**
     * @return \Generator
     */
    public function provideWrongCredentials()
    {
        yield array('/fr/login', '/fr/login', ['username' => '', 'password' => '']);
        yield array('/en/login', '/en/login', ['username' => '', 'password' => '']);
    }

    /**
     * @return \Generator
     */
    public function provideRightCredentials()
    {
        yield array('/fr/login', '/fr/login', ['username' => 'toto', 'password' => 'toto']);
        yield array('/en/login', '/en/login', ['username' => 'toto', 'password' => 'toto']);
    }

    /**
     * @return \Generator
     */
    public function provideEmptyCredentials()
    {
        yield array(['username' => '', 'password' => '']);
    }

    /**
     * @return \Generator
     */
    public function provideValidCredentials()
    {
        yield array(['username' => 'toto', 'password' => 'toto']);
    }
}
