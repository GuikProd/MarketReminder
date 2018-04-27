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

namespace App\UI\Form\FormHandler\Interfaces;

use App\Application\Helper\Image\Interfaces\ImageRetrieverHelperInterface;
use App\Application\Helper\Image\Interfaces\ImageUploaderHelperInterface;
use App\Domain\Builder\Interfaces\ImageBuilderInterface;
use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Infra\GCP\CloudStorage\Interfaces\CloudStoragePersisterHelperInterface;
use App\UI\Presenter\User\Interfaces\UserEmailPresenterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Interface RegisterTypeHandlerInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface RegisterTypeHandlerInterface
{
    /**
     * RegisterTypeHandlerInterface constructor.
     *
     * @param CloudStoragePersisterHelperInterface  $cloudStoragePersisterHelper
     * @param EventDispatcherInterface              $eventDispatcher
     * @param EncoderFactoryInterface               $passwordEncoderFactory
     * @param ImageBuilderInterface                 $imageBuilder
     * @param ImageUploaderHelperInterface          $imageUploaderHelper
     * @param ImageRetrieverHelperInterface         $imageRetrieverHelper
     * @param UserBuilderInterface                  $userBuilder
     * @param UserEmailPresenterInterface           $presenter
     * @param UserRepositoryInterface               $userRepository
     * @param ValidatorInterface                    $validator
     */
    public function __construct(
        CloudStoragePersisterHelperInterface $cloudStoragePersisterHelper,
        EventDispatcherInterface $eventDispatcher,
        EncoderFactoryInterface $passwordEncoderFactory,
        ImageBuilderInterface $imageBuilder,
        ImageUploaderHelperInterface $imageUploaderHelper,
        ImageRetrieverHelperInterface $imageRetrieverHelper,
        UserBuilderInterface $userBuilder,
        UserEmailPresenterInterface $presenter,
        UserRepositoryInterface $userRepository,
        ValidatorInterface $validator
    );

    /**
     * @param FormInterface $registerForm  The RegisterType Form
     *
     * @return bool  If the handling process has succeed
     */
    public function handle(FormInterface $registerForm): bool;
}
