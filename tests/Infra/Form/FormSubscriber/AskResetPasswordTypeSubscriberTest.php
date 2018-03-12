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

use App\Infra\Form\FormSubscriber\AskResetPasswordTypeSubscriber;
use App\Infra\Form\FormSubscriber\Interfaces\AskResetPasswordTypeSubscriberInterface;
use App\UI\Form\Type\AskResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AskResetPasswordTypeSubscriberTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordTypeSubscriberTest extends KernelTestCase
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

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

        $this->translator = static::$kernel->getContainer()
                                           ->get('translator');

        $this->formFactory = static::$kernel->getContainer()
                                            ->get('form.factory');

        $this->entityManager = static::$kernel->getContainer()
                                              ->get('doctrine.orm.entity_manager');
    }

    public function testItImplements()
    {
        $askResetPasswordTypeSubscriber = new AskResetPasswordTypeSubscriber(
                                            $this->translator,
                                            $this->entityManager
                                          );

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
        $askResetPasswordTypeSubscriber = new AskResetPasswordTypeSubscriber(
                                                  $this->translator,
                                                $this->entityManager
                                              );

        static::assertArrayHasKey(
            FormEvents::SUBMIT,
            $askResetPasswordTypeSubscriber::getSubscribedEvents()
        );
    }

    public function testWrongSubmittedData()
    {
        $askResetPasswordType = $this->formFactory->create(AskResetPasswordType::class);
        $formEventMock = $this->createMock(FormEvent::class);

        $askResetPasswordType->submit(['username' => 'Tutu', 'email' => 'tutu@gmail.com']);

        $formEventMock->method('getData')
                      ->willReturn($askResetPasswordType->getData());

        $formEventMock->method('getForm')
                      ->willReturn($askResetPasswordType);

        $askResetPasswordTypeSubscriber = new AskResetPasswordTypeSubscriber(
                                                $this->translator,
                                                $this->entityManager
                                              );
        $askResetPasswordTypeSubscriber->onSubmit($formEventMock);

        static::assertGreaterThan(
            0,
            $formEventMock->getForm()->getErrors()->count()
        );
    }

    /**
     * Allow to test the subscriber with correct data, the last test is locked to
     * less than "2" due to the CSRF protection.
     */
    public function testRightSubmittedData()
    {
        $askResetPasswordType = $this->formFactory->create(AskResetPasswordType::class);
        $formEventMock = $this->createMock(FormEvent::class);

        $askResetPasswordType->submit(['username' => 'Toto', 'email' => 'toto@gmail.com']);

        $formEventMock->method('getData')
                      ->willReturn($askResetPasswordType->getData());

        $formEventMock->method('getForm')
                      ->willReturn($askResetPasswordType);

        $askResetPasswordTypeSubscriber = new AskResetPasswordTypeSubscriber(
                                                  $this->translator,
                                                  $this->entityManager
                                              );
        $askResetPasswordTypeSubscriber->onSubmit($formEventMock);

        static::assertSame(
            'Toto',
            $formEventMock->getData()->username
        );

        static::assertSame(
            'toto@gmail.com',
            $formEventMock->getData()->email
        );

        static::assertLessThan(
            2,
            $formEventMock->getForm()->getErrors()->count()
        );
    }
}
