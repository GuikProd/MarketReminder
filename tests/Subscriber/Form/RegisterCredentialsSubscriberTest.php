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

namespace tests\Subscriber\Form;

use App\Interactor\UserInteractor;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use App\Subscriber\Form\RegisterCredentialsSubscriber;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RegisterCredentialsSubscriberTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterCredentialsSubscriberTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->entityManager = static::bootKernel()->getContainer()
                                                   ->get('doctrine.orm.entity_manager');
    }

    public function testSubscribedEvents()
    {
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $subscriber = new RegisterCredentialsSubscriber($translatorMock, $entityManagerMock);

        static::assertArrayHasKey(
            FormEvents::SUBMIT,
            $subscriber::getSubscribedEvents()
        );
    }

    public function testEmptyCredentials()
    {
        $eventsMock = $this->createMock(FormEvent::class);
        $translatorMock = $this->createMock(TranslatorInterface::class);

        $subscriber = new RegisterCredentialsSubscriber($translatorMock, $this->entityManager);

        static::assertNull(
            $subscriber->checkCredentials($eventsMock)
        );
    }

    public function testRightCredentialsCheck()
    {
        $eventsMock = $this->createMock(FormEvent::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $userInteractorMock = $this->createMock(UserInteractor::class);
        $translatorMock = $this->createMock(TranslatorInterface::class);

        $userInteractorMock->method('getUsername')
                           ->willReturn('Toto');

        $userInteractorMock->method('getEmail')
                           ->willReturn('toto@gmail.com');

        $eventsMock->method('getData')
                   ->willReturn($userInteractorMock);

        $eventsMock->method('getForm')
                   ->willReturn($formInterfaceMock);

        $subscriber = new RegisterCredentialsSubscriber($translatorMock, $this->entityManager);

        static::assertNull(
            $subscriber->checkCredentials($eventsMock)
        );
    }

    public function testWrongCredentialsCheck()
    {
        $eventsMock = $this->createMock(FormEvent::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);
        $userInteractorMock = $this->createMock(UserInteractor::class);
        $translatorMock = $this->createMock(TranslatorInterface::class);

        $userInteractorMock->method('getUsername')
                           ->willReturn('HP');

        $userInteractorMock->method('getEmail')
                           ->willReturn('hp@gmail.com');

        $eventsMock->method('getData')
                   ->willReturn($userInteractorMock);

        $eventsMock->method('getForm')
                   ->willReturn($formInterfaceMock);

        $subscriber = new RegisterCredentialsSubscriber($translatorMock, $this->entityManager);

        static::assertNull(
            $subscriber->checkCredentials($eventsMock)
        );
    }
}
