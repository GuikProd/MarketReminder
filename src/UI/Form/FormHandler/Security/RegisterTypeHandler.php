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

namespace App\UI\Form\FormHandler\Security;

use App\Application\Helper\Image\Interfaces\ImageRetrieverHelperInterface;
use App\Application\Helper\Image\Interfaces\ImageUploaderHelperInterface;
use App\Application\Messenger\Message\Factory\Interfaces\MessageFactoryInterface;
use App\Application\Messenger\Message\SessionMessage;
use App\Domain\Builder\Interfaces\ImageBuilderInterface;
use App\Domain\Factory\Interfaces\UserFactoryInterface;
use App\Domain\Models\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Infra\GCP\CloudStorage\Helper\Interfaces\CloudStorageWriterHelperInterface;
use App\UI\Form\FormHandler\Security\Interfaces\RegisterTypeHandlerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegisterTypeHandler.
 *
 * @package App\UI\Form\FormHandler\Security
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RegisterTypeHandler implements RegisterTypeHandlerInterface
{
    /**
     * @var CloudStorageWriterHelperInterface
     */
    private $cloudStoragePersisterHelper;

    /**
     * @var EncoderFactoryInterface
     */
    private $passwordEncoderFactory;

    /**
     * @var ImageBuilderInterface
     */
    private $imageBuilder;

    /**
     * @var ImageUploaderHelperInterface
     */
    private $imageUploaderHelper;

    /**
     * @var ImageRetrieverHelperInterface
     */
    private $imageRetrieverHelper;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var MessageFactoryInterface
     */
    private $messageFactory;

    /**
     * @var UserFactoryInterface
     */
    private $userFactory;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * {@inheritdoc}
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
    ) {
        $this->cloudStoragePersisterHelper = $cloudStoragePersisterHelper;
        $this->passwordEncoderFactory = $passwordEncoderFactory;
        $this->imageBuilder = $imageBuilder;
        $this->imageUploaderHelper = $imageUploaderHelper;
        $this->imageRetrieverHelper = $imageRetrieverHelper;
        $this->messageBus = $messageBus;
        $this->messageFactory = $messageFactory;
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $registerForm, Request $request): bool
    {
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            if (!\is_null($registerForm->getData()->profileImage)) {
                $this->imageUploaderHelper->generateFilename($registerForm->getData()->profileImage);
                $this->imageUploaderHelper->upload($registerForm->getData()->profileImage);

                $this->imageBuilder->build(
                    $this->imageUploaderHelper->getFileName(),
                    $this->imageUploaderHelper->getFileName(),
                    $this->imageRetrieverHelper
                                 ->getGoogleStoragePublicUrl().$this->imageUploaderHelper->getFileName()
                );
            }

            $password = $this->passwordEncoderFactory->getEncoder(User::class)
                                                     ->encodePassword($registerForm->getData()->password, null);

            $user = $this->userFactory->createFromUI(
                $registerForm->getData()->username,
                $registerForm->getData()->email,
                $password,
                $this->imageBuilder->getImage()
            );

            $errors = $this->validator->validate($user, null, ['User', 'registration']);

            if (\count($errors) > 0) {

                $this->messageBus->dispatch(new SessionMessage(
                    'failure', 'user.invalid_credentials'
                ));

                return false;
            }

            $this->userRepository->save($user);

            $message = $this->messageFactory->createUserMessage([
                '_locale' => $request->getLocale(),
                '_topic' => 'registration',
                'id' => $user->getId()->toString(),
                'user' => [
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'creationDate' => $user->getCreationDate(),
                    'validationToken' => $user->getValidationToken()
                ]
            ]);

            $this->messageBus->dispatch($message);

            $this->messageBus->dispatch(new SessionMessage(
                'success', 'user.account_created'
            ));

            return true;
        }

        return false;
    }
}
