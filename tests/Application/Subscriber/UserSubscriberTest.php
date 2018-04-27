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

use App\Application\Event\User\UserCreatedEvent;
use App\Application\Event\User\UserResetPasswordEvent;
use App\Application\Event\User\UserValidatedEvent;
use App\Application\Subscriber\Interfaces\UserSubscriberInterface;
use App\Application\Subscriber\UserSubscriber;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class UserSubscriberTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserSubscriberTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var string
     */
    private $emailSender;

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
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->emailSender = 'test@marketReminder.com';
        $this->swiftMailer = $this->createMock(\Swift_Mailer::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->twig = $this->createMock(Environment::class);
    }

    public function testItImplements()
    {
        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->translator,
            $this->twig
        );

        static::assertClassHasAttribute('swiftMailer', UserSubscriber::class);
        static::assertInstanceOf(UserSubscriberInterface::class, $userSubscriber);
        static::assertInstanceOf(UserSubscriberInterface::class, $userSubscriber);
    }

    /**
     * @group Blackfire
     *
     * @doestNotPerformAssertions
     */
    public function testBlackfireProfilingAndUserCreatedEventIsListened()
    {
        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->translator,
            $this->twig
        );
    }

    public function testUserCreatedEventIsListened()
    {
        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->translator,
            $this->twig
        );

        static::assertArrayHasKey(UserCreatedEvent::NAME, $userSubscriber::getSubscribedEvents());
    }

    public function testUserValidatedEventIsListened()
    {
        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->translator,
            $this->twig
        );

        static::assertArrayHasKey(
            UserValidatedEvent::NAME,
            $userSubscriber::getSubscribedEvents()
        );
    }

    public function testUserResetPasswordEventIsListened()
    {
        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->translator,
            $this->twig
        );

        static::assertArrayHasKey(
            UserResetPasswordEvent::NAME,
            $userSubscriber::getSubscribedEvents()
        );
    }

    public function testUserResetPasswordEventLogicIsTriggered()
    {
        $userResetPasswordEventMock = $this->createMock(UserResetPasswordEvent::class);

        $userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->translator,
            $this->twig
        );

        static::assertNull(
            $userSubscriber->onUserResetPassword($userResetPasswordEventMock)
        );
    }
}
