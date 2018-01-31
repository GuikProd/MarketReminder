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

namespace tests\Handler;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\FormHandler\RegisterTypeHandler;
use Symfony\Component\Workflow\Registry;
use App\Builder\Interfaces\ImageBuilderInterface;
use App\Helper\Interfaces\ImageUploaderHelperInterface;
use App\Helper\Interfaces\ImageRetrieverHelperInterface;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegisterTypeHandlerTest
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeHandlerTest extends TestCase
{
    public function testItImplement()
    {
        $registryMock = $this->createMock(Registry::class);

        $imageBuilderMock = $this->createMock(ImageBuilderInterface::class);

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $imageUploaderHelperMock = $this->createMock(ImageUploaderHelperInterface::class);

        $userPasswordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);

        $imageRetrieverHelperMock = $this->createMock(ImageRetrieverHelperInterface::class);

        $registerTypeHandler = new RegisterTypeHandler(
            $registryMock,
            $imageBuilderMock,
            $entityManagerMock,
            $imageUploaderHelperMock,
            $userPasswordEncoderMock,
            $imageRetrieverHelperMock
        );

        static::assertInstanceOf(
            RegisterTypeHandlerInterface::class,
            $registerTypeHandler
        );
    }
}
