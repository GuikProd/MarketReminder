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

use App\Application\Event\UserEvent;
use App\Application\Subscriber\Interfaces\UserSubscriberInterface;
use App\Application\Subscriber\UserSubscriber;
use App\Domain\Event\Interfaces\UserEventInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Infra\Redis\RedisConnector;
use App\Infra\Redis\Translation\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\Redis\Translation\CloudTranslationRepository;
use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Presenter;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

/**
 * Class UserSubscriberSystemTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserSubscriberSystemTest extends KernelTestCase
{
    use TestCaseTrait;

    /**
     * @var string
     */
    private $emailSender;

    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $redisTranslationRepository;

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
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var UserEventInterface
     */
    private $userEvent;

    /**
     * @var UserSubscriberInterface
     */
    private $userSubscriber;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        static::bootKernel();

        $this->emailSender = static::$kernel->getContainer()->getParameter('email.sender_address');
        $this->swiftMailer = static::$kernel->getContainer()->get('mailer');
        $this->twig = static::$kernel->getContainer()->get('twig');

        $this->requestStack = $this->createMock(RequestStack::class);
        $this->user = $this->createMock(UserInterface::class);
        $request = $this->createMock(Request::class);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);
        $request->method('getLocale')->willReturn('fr');

        $redisConnector = new RedisConnector(
            static::$kernel->getContainer()->getParameter('redis.dsn'),
            static::$kernel->getContainer()->getParameter('redis.namespace_test')
        );

        $this->redisTranslationRepository = new CloudTranslationRepository($redisConnector);

        $this->presenter = new Presenter($this->redisTranslationRepository);

        $this->userEvent = new UserEvent($this->user);

        $this->userSubscriber = new UserSubscriber(
            $this->emailSender,
            $this->swiftMailer,
            $this->twig,
            $this->presenter,
            $this->requestStack
        );
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testUserAskResetPasswordEventIsTriggered()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 2.7MB', 'User subscriber user reset ask reset password memory usage');
        $configuration->assert('main.network_in < 45B', 'User subscriber user ask reset password network in');
        $configuration->assert('main.network_out < 360B', 'User subscriber user ask reset password network out');

        $this->assertBlackfire($configuration, function() {
            $this->userSubscriber->onUserAskResetPasswordEvent($this->userEvent);
        });
    }
}
