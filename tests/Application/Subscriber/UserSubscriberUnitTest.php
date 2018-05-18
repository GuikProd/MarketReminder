<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Application\Subscriber;

use App\Application\Event\User\UserEvent;
use App\Application\Subscriber\Interfaces\UserSubscriberInterface;
use App\Application\Subscriber\UserSubscriber;
use App\Infra\Redis\Translation\Interfaces\RedisTranslationRepositoryInterface;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Presenter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class UserSubscriberUnitTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserSubscriberUnitTest extends TestCase
{
    /**
     * @var string
     */
    private $emailSender;

    /**
     * @var RedisTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var PresenterInterface
     */
    private $presenter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->emailSender = 'test@marketReminder.com';
        $this->redisTranslationRepository = $this->createMock(RedisTranslationRepositoryInterface::class);
        $this->swiftMailer = $this->createMock(\Swift_Mailer::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->twig = $this->createMock(Environment::class);

        $this->presenter = new Presenter($this->redisTranslationRepository);
    }

    public function testItImplements()
    {
        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->translator,
            $this->twig,
            $this->presenter
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
            $this->translator,
            $this->twig,
            $this->presenter
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
    public function testUserResetPasswordEventLogicIsTriggered()
    {
        $userResetPasswordEventMock = $this->createMock(UserEvent::class);

        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->translator,
            $this->twig,
            $this->presenter
        );

        static::assertNull(
            $userSubscriber->onUserResetPassword($userResetPasswordEventMock)
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testUserValidatedEventLogicIsTriggered()
    {
        $userValidatedEvent = $this->createMock(UserEvent::class);

        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->translator,
            $this->twig,
            $this->presenter
        );

        static::assertNull(
            $userSubscriber->onUserValidated($userValidatedEvent)
        );
    }
}
