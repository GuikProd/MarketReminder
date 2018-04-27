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

namespace App\UI\Form\FormHandler;

use App\Application\Event\User\UserCreatedEvent;
use App\Application\Event\SessionMessageEvent;
use App\Application\Helper\Image\Interfaces\ImageRetrieverHelperInterface;
use App\Application\Helper\Image\Interfaces\ImageUploaderHelperInterface;
use App\Domain\Builder\Interfaces\ImageBuilderInterface;
use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\Models\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Infra\GCP\CloudStorage\Interfaces\CloudStoragePersisterHelperInterface;
use App\UI\Form\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use App\UI\Presenter\User\Interfaces\UserEmailPresenterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegisterTypeHandler.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeHandler implements RegisterTypeHandlerInterface
{
    /**
     * @var CloudStoragePersisterHelperInterface
     */
    private $cloudStoragePersisterHelper;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

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
     * @var UserBuilderInterface
     */
    private $userBuilder;

    /**
     * @var UserEmailPresenterInterface
     */
    private $userEmailPresenter;

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
    ) {
        $this->cloudStoragePersisterHelper = $cloudStoragePersisterHelper;
        $this->eventDispatcher = $eventDispatcher;
        $this->passwordEncoderFactory = $passwordEncoderFactory;
        $this->imageBuilder = $imageBuilder;
        $this->imageUploaderHelper = $imageUploaderHelper;
        $this->imageRetrieverHelper = $imageRetrieverHelper;
        $this->userBuilder = $userBuilder;
        $this->userEmailPresenter = $presenter;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $registerForm): bool
    {
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            $encoder = $this->passwordEncoderFactory->getEncoder(User::class);

            if (!is_null($registerForm->getData()->profileImage)) {
                $this->imageUploaderHelper->generateFilename($registerForm->getData()->profileImage);
                $this->imageUploaderHelper->upload($registerForm->getData()->profileImage);

                $this->imageBuilder->build(
                    $this->imageUploaderHelper->getFileName(),
                    $this->imageUploaderHelper->getFileName(),
                    $this->imageRetrieverHelper->getGoogleStoragePublicUrl().$this->imageUploaderHelper->getFileName()
                );
            }

            $this->userBuilder->createFromRegistration(
                $registerForm->getData()->email,
                $registerForm->getData()->username,
                $registerForm->getData()->password,
                \Closure::fromCallable([$encoder, 'encodePassword']),
                $registerForm->getData()->validationToken,
                $this->imageBuilder->getImage() ?: null
            );

            $errors = $this->validator->validate($this->userBuilder->getUser(), null, ['User', 'registration']);

            if (count($errors) > 0) {
                $this->eventDispatcher->dispatch(
                    SessionMessageEvent::NAME,
                    new SessionMessageEvent(
                        'failure',
                        'user.invalid_credentials'
                    )
                );

                return false;
            }

            $this->userRepository->save($this->userBuilder->getUser());

            $this->userEmailPresenter->prepareOptions([
                'email' => [
                    'content' => [
                        'first_part' => 'user.registration.welcome.content_first_part',
                        'second_part' => 'user.registration.welcome.content_second_part',
                        'link' => [
                            'text' => 'user.registration.welcome.content.link.text'
                        ]
                    ],
                    'header' => 'user.registration.welcome.header',
                    'subject' => 'user.registration.welcome.header',
                    'to' => $this->userBuilder->getUser()
                ]
            ]);

            $this->eventDispatcher->dispatch(
                UserCreatedEvent::NAME,
                new UserCreatedEvent(
                    $this->userBuilder->getUser(),
                    $this->userEmailPresenter
                )
            );

            $this->eventDispatcher->dispatch(
                SessionMessageEvent::NAME,
                new SessionMessageEvent(
                    'success',
                    'user.account_created'
                )
            );

            return true;
        }

        return false;
    }
}
