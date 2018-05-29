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

namespace App\Tests\Application\Subscriber;

use App\Application\Event\UserEvent;
use App\Application\Subscriber\Interfaces\UserSubscriberInterface;
use App\Application\Subscriber\UserSubscriber;
use App\Domain\Event\Interfaces\UserEventInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Infra\Redis\Translation\Interfaces\CloudTranslationRepositoryInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Presenter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

/**
 * Class UserSubscriberUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class UserSubscriberUnitTest extends TestCase
{
    /**
     * @var string
     */
    private $emailSender;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * @var UserEventInterface
     */
    private $userEvent;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->emailSender = 'test@marketReminder.com';
        $this->redisTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->swiftMailer = $this->createMock(\Swift_Mailer::class);
        $this->twig = $this->createMock(Environment::class);

        $request = $this->createMock(Request::class);

        $request->method('getLocale')->willReturn('fr');
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $this->presenter = new Presenter($this->redisTranslationRepository);
        $this->userEvent = new UserEvent($this->createMock(UserInterface::class));
    }

    public function testItImplements()
    {
        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->twig,
            $this->presenter,
            $this->requestStack
        );

        static::assertClassHasAttribute('swiftMailer', UserSubscriber::class);
        static::assertInstanceOf(UserSubscriberInterface::class, $userSubscriber);
        static::assertInstanceOf(UserSubscriberInterface::class, $userSubscriber);
    }

    public function testEventsAreListened()
    {
        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->twig,
            $this->presenter,
            $this->requestStack
        );

        static::assertArrayHasKey(
            UserEvent::USER_ASK_RESET_PASSWORD,
            $userSubscriber::getSubscribedEvents()
        );
        static::assertArrayHasKey(
            UserEvent::USER_CREATED,
            $userSubscriber::getSubscribedEvents()
        );
        static::assertArrayHasKey(
            UserEvent::USER_RESET_PASSWORD,
            $userSubscriber::getSubscribedEvents()
        );
        static::assertArrayHasKey(
            UserEvent::USER_VALIDATED,
            $userSubscriber::getSubscribedEvents()
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testUserAskResetPasswordEventLogicIsTriggered()
    {
        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->twig,
            $this->presenter,
            $this->requestStack
        );

        $userSubscriber->onUserAskResetPasswordEvent($this->userEvent);

        static::assertSame('fr', $this->presenter->getViewOptions()['_locale']);
        static::assertInstanceOf(
            UserInterface::class,
            $this->presenter->getViewOptions()['user']
        );
        static::assertSame(
            'user.ask_reset_password.content',
            $this->presenter->getPage()['content']['key']
        );
        static::assertSame(
            'user.ask_reset_password.link.text',
            $this->presenter->getPage()['link']['key']
        );
        static::assertSame(
            'user.ask_reset_password.header',
            $this->presenter->getPage()['header']['key']
        );
        static::assertSame(
            'user.ask_reset_password.header',
            $this->presenter->getPage()['subject']['key']
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testUserCreatedEventIsTriggered()
    {
        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->twig,
            $this->presenter,
            $this->requestStack
        );

        $userSubscriber->onUserCreated($this->userEvent);

        static::assertSame('fr', $this->presenter->getViewOptions()['_locale']);
        static::assertInstanceOf(
            UserInterface::class,
            $this->presenter->getViewOptions()['user']
        );
        static::assertSame(
            'user.registration.welcome.content_first_part',
            $this->presenter->getPage()['content_first']['key']
        );
        static::assertSame(
            'user.registration.welcome.content_second_part',
            $this->presenter->getPage()['content_second']['key']
        );
        static::assertSame(
            'user.registration.welcome.content.link.text',
            $this->presenter->getPage()['link']['key']
        );
        static::assertSame(
            'user.registration.welcome.header',
            $this->presenter->getPage()['header']['key']
        );
        static::assertSame(
            'user.registration.welcome.header',
            $this->presenter->getPage()['subject']['key']
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testUserResetPasswordEventIsTriggered()
    {
        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->twig,
            $this->presenter,
            $this->requestStack
        );

        $userSubscriber->onUserResetPassword($this->userEvent);

        static::assertSame('fr', $this->presenter->getViewOptions()['_locale']);
        static::assertInstanceOf(
            UserInterface::class,
            $this->presenter->getViewOptions()['user']
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testUserValidatedEventLogicIsTriggered()
    {
        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->twig,
            $this->presenter,
            $this->requestStack
        );

        $userSubscriber->onUserValidated($this->userEvent);

        static::assertSame('fr', $this->presenter->getViewOptions()['_locale']);
        static::assertInstanceOf(
            UserInterface::class,
            $this->presenter->getViewOptions()['user']
        );
    }
}
