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

namespace App\Tests\Infra\Form\FormSubscriber;

use App\Domain\UseCase\UserResetPassword\DTO\Interfaces\UserResetPasswordDTOInterface;
use App\Domain\UseCase\UserResetPassword\DTO\UserResetPasswordDTO;
use App\Infra\Form\FormSubscriber\AskResetPasswordTypeSubscriber;
use App\Infra\Form\FormSubscriber\Interfaces\AskResetPasswordTypeSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * Class AskResetPasswordTypeSubscriberTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordTypeSubscriberTest extends KernelTestCase
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
        static::bootKernel();

        $this->entityManager = static::$kernel->getContainer()
                                              ->get('doctrine.orm.entity_manager');
    }

    public function testItImplements()
    {
        $askResetPasswordTypeSubscriber = new AskResetPasswordTypeSubscriber($this->entityManager);

        static::assertInstanceOf(
            EventSubscriberInterface::class,
            $askResetPasswordTypeSubscriber
        );

        static::assertInstanceOf(
            AskResetPasswordTypeSubscriberInterface::class,
            $askResetPasswordTypeSubscriber
        );
    }

    public function testSubscribedEvents()
    {
        $askResetPasswordTypeSubscriber = new AskResetPasswordTypeSubscriber($this->entityManager);

        static::assertArrayHasKey(
            FormEvents::SUBMIT,
            $askResetPasswordTypeSubscriber::getSubscribedEvents()
        );
    }

    public function testWrongSubmittedData()
    {
        $formEventMock = $this->createMock(FormEvent::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $userResetPasswordDTOMock = new UserResetPasswordDTO('tutu@gmail.com', 'Tutu');

        $formEventMock->method('getData')
                      ->willReturn($userResetPasswordDTOMock);

        $formEventMock->method('getForm')
                      ->willReturn($formInterfaceMock);

        $askResetPasswordTypeSubscriber = new AskResetPasswordTypeSubscriber($this->entityManager);
        $askResetPasswordTypeSubscriber->onSubmit($formEventMock);

        var_dump($formEventMock->getForm()->getErrors());

        static::assertGreaterThan(
            0,
            $formEventMock->getForm()->getErrors()
        );
    }
}
