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

namespace App\Tests\UI\Form\Type;

use App\Domain\UseCase\UserResetPassword\DTO\Interfaces\UserResetPasswordDTOInterface;
use App\Infra\Form\FormSubscriber\AskResetPasswordTypeSubscriber;
use App\Infra\Form\FormSubscriber\Interfaces\AskResetPasswordTypeSubscriberInterface;
use App\UI\Form\Type\AskResetPasswordType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class AskResetPasswordTypeTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordTypeTest extends TypeTestCase
{
    /**
     * @var AskResetPasswordTypeSubscriberInterface
     */
    private $askResetPasswordTypeSubscriber;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->askResetPasswordTypeSubscriber = $this->createMock(AskResetPasswordTypeSubscriber::class);

        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        $type = new AskResetPasswordType($this->askResetPasswordTypeSubscriber);

        return [
            new PreloadedExtension([$type], [])
        ];
    }

    public function testSubmittedDataIsSentToDTO()
    {
        $form = $this->factory->create(AskResetPasswordType::class);
        $form->submit(['username' => 'Toto', 'email' => 'toto@gmail.com']);

        static::assertInstanceOf(
            FormInterface::class,
            $form
        );

        static::assertArrayHasKey(
            'validation_groups',
            $form->getConfig()->getOptions()
        );

        static::assertInstanceOf(
            UserResetPasswordDTOInterface::class,
            $form->getData()
        );

        static::assertSame(
            UserResetPasswordDTOInterface::class,
            $form->getConfig()->getDataClass()
        );

        static::assertNotNull(
            $form->getData()
        );
    }
}
