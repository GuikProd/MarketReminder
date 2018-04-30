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

use App\Domain\UseCase\UserResetPassword\DTO\Interfaces\UserNewPasswordDTOInterface;
use App\UI\Form\Type\ResetPasswordType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ResetPasswordTypeTest.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordTypeTest extends TypeTestCase
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
            new ValidatorExtension($this->validator),
        );
    }

    public function testFormCreation()
    {
        $resetPasswordType = $this->factory->create(ResetPasswordType::class);

        static::assertInstanceOf(FormInterface::class, $resetPasswordType);
        static::assertArrayHasKey('validation_groups', $resetPasswordType->getConfig()->getOptions());
        static::assertTrue($resetPasswordType->isSynchronized());
        static::assertTrue($resetPasswordType->isEmpty());
    }

    public function testWrongDataAreSubmitted()
    {
        $resetPasswordType = $this->factory->create(ResetPasswordType::class);
        $resetPasswordType->submit([
            'password' => [
                'first' => 'Ie1FDLTOTo',
                'second' => 'Ie1FDLTOT'
            ]
        ]);

        static::assertTrue($resetPasswordType->isValid());
        static::assertInstanceOf(UserNewPasswordDTOInterface::class, $resetPasswordType->getData());
        static::assertNull($resetPasswordType->getData()->password);
    }

    public function testGoodDataAreSubmitted()
    {
        $resetPasswordType = $this->factory->create(ResetPasswordType::class);
        $resetPasswordType->submit([
            'password' => [
                'first' => 'Ie1FDLTOTo',
                'second' => 'Ie1FDLTOTo',
            ],
        ]);

        static::assertTrue($resetPasswordType->isValid());
        static::assertInstanceOf(UserNewPasswordDTOInterface::class, $resetPasswordType->getData());
        static::assertSame('Ie1FDLTOTo', $resetPasswordType->getData()->password);
    }
}
