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

namespace App\Tests\Domain\Builder;

use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\Builder\UserBuilder;
use App\Domain\Models\Interfaces\ImageInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\Models\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class UserBuilderTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class UserBuilderTest extends TestCase
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->encoderFactory = $this->createMock(EncoderFactoryInterface::class);
        $this->encoderFactory->method('getEncoder')
                             ->willReturn(new BCryptPasswordEncoder(13));
    }

    public function testItImplements()
    {
        $userBuilder = new UserBuilder();

        static::assertInstanceOf(
            UserBuilderInterface::class,
            $userBuilder
        );
    }

    /**
     * @throws \Exception
     */
    public function testUserCreation()
    {
        $imageMock = $this->createMock(ImageInterface::class);

        $encoder = $this->encoderFactory->getEncoder(User::class);

        $userBuilder = new UserBuilder();

        $userBuilder->createFromRegistration(
            'toto@gmail.com',
            'toto',
            'Ie1FDLTOTO',
            \Closure::fromCallable([$encoder, 'encodePassword']),
            $imageMock
        );

        static::assertInstanceOf(
            UserInterface::class,
            $userBuilder->getUser()
        );
    }
}
