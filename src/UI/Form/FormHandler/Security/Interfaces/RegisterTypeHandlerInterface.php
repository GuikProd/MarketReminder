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

namespace App\UI\Form\FormHandler\Security\Interfaces;

use App\Application\Helper\Image\Interfaces\ImageRetrieverHelperInterface;
use App\Application\Helper\Image\Interfaces\ImageUploaderHelperInterface;
use App\Application\Messenger\Message\Factory\Interfaces\MessageFactoryInterface;
use App\Domain\Builder\Interfaces\ImageBuilderInterface;
use App\Domain\Factory\Interfaces\UserFactoryInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageWriterHelperInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Interface RegisterTypeHandlerInterface.
 *
 * @package App\UI\Form\FormHandler\Security\Interfaces
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface RegisterTypeHandlerInterface
{
    /**
     * RegisterTypeHandlerInterface constructor.
     *
     * @param CloudStorageWriterHelperInterface $cloudStoragePersisterHelper
     * @param EncoderFactoryInterface           $passwordEncoderFactory
     * @param ImageBuilderInterface             $imageBuilder
     * @param ImageUploaderHelperInterface      $imageUploaderHelper
     * @param ImageRetrieverHelperInterface     $imageRetrieverHelper
     * @param MessageBusInterface               $messageBus
     * @param MessageFactoryInterface           $messageFactory
     * @param UserFactoryInterface              $userFactory
     * @param UserRepositoryInterface           $userRepository
     * @param ValidatorInterface                $validator
     */
    public function __construct(
        CloudStorageWriterHelperInterface $cloudStoragePersisterHelper,
        EncoderFactoryInterface $passwordEncoderFactory,
        ImageBuilderInterface $imageBuilder,
        ImageUploaderHelperInterface $imageUploaderHelper,
        ImageRetrieverHelperInterface $imageRetrieverHelper,
        MessageBusInterface $messageBus,
        MessageFactoryInterface $messageFactory,
        UserFactoryInterface $userFactory,
        UserRepositoryInterface $userRepository,
        ValidatorInterface $validator
    );

    /**
     * @param FormInterface $registerForm The RegisterType Form
     * @param Request       $request      The Request (used for session message and locale).
     *
     * @return bool                       If the handling process has succeed
     */
    public function handle(
        FormInterface $registerForm,
        Request $request
    ): bool;
}
