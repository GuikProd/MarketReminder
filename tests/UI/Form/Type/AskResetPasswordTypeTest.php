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
use App\UI\Form\Type\AskResetPasswordType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AskResetPasswordTypeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordTypeTest extends TypeTestCase
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * {@inheritdoc}
     */
    protected function getExtensions()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)));

        return array(
            new ValidatorExtension($this->validator)
        );
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
