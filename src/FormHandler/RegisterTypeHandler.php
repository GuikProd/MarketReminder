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

namespace App\FormHandler;

use Symfony\Component\Workflow\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use App\Builder\Interfaces\UserBuilderInterface;
use App\Builder\Interfaces\ImageBuilderInterface;
use App\Helper\Interfaces\ImageUploaderHelperInterface;
use App\Helper\Interfaces\ImageRetrieverHelperInterface;
use App\FormHandler\Interfaces\RegisterTypeHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegisterTypeHandler.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterTypeHandler implements RegisterTypeHandlerInterface
{
    /**
     * @var Registry
     */
    private $workflowRegistry;

    /**
     * @var ImageBuilderInterface
     */
    private $imageBuilderInterface;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var ImageUploaderHelperInterface
     */
    private $imageUploaderHelperInterface;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoderInterface;

    /**
     * @var ImageRetrieverHelperInterface
     */
    private $imageRetrieverHelperInterface;

    /**
     * RegisterTypeHandler constructor.
     *
     * @param Registry $workflowRegistry
     * @param ImageBuilderInterface $imageBuilderInterface
     * @param EntityManagerInterface $entityManagerInterface
     * @param ImageUploaderHelperInterface $imageUploaderHelperInterface
     * @param UserPasswordEncoderInterface $userPasswordEncoderInterface
     * @param ImageRetrieverHelperInterface $imageRetrieverHelperInterface
     */
    public function __construct(
        Registry $workflowRegistry,
        ImageBuilderInterface $imageBuilderInterface,
        EntityManagerInterface $entityManagerInterface,
        ImageUploaderHelperInterface $imageUploaderHelperInterface,
        UserPasswordEncoderInterface $userPasswordEncoderInterface,
        ImageRetrieverHelperInterface $imageRetrieverHelperInterface
    ) {
        $this->workflowRegistry = $workflowRegistry;
        $this->imageBuilderInterface = $imageBuilderInterface;
        $this->entityManagerInterface = $entityManagerInterface;
        $this->imageUploaderHelperInterface = $imageUploaderHelperInterface;
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
        $this->imageRetrieverHelperInterface = $imageRetrieverHelperInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $registerForm, UserBuilderInterface $userBuilder): bool
    {
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            $this->imageUploaderHelperInterface
                 ->store($registerForm->get('profileImage')->getData())
                 ->upload();

            $this->imageBuilderInterface
                 ->createImage()
                 ->withCreationDate(new \DateTime())
                 ->withAlt($this->imageUploaderHelperInterface->getFileName())
                 ->withPublicUrl(
                     $this->imageRetrieverHelperInterface->getGoogleStoragePublicUrl()
                     .
                     $this->imageRetrieverHelperInterface->getBucketName()
                     .
                     '/'
                     .
                     $this->imageUploaderHelperInterface->getFileName()
                 );

            $userBuilder
                ->withCreationDate(new \DateTime())
                ->withPassword(
                    $this->userPasswordEncoderInterface
                         ->encodePassword(
                             $userBuilder->getUser(),
                             $userBuilder->getUser()->getPlainPassword()
                         )
                )
                ->withRole('ROLE_USER')
                ->withValidationToken(
                    crypt(
                        str_rot13(
                            str_shuffle(
                                $userBuilder->getUser()->getEmail()
                            )
                        ),
                        $userBuilder->getUser()->getUsername()
                    )
                )
                ->withActive(false)
                ->withValidated(false)
                ->withProfileImage($this->imageBuilderInterface->getImage())
            ;

            $workflow = $this->workflowRegistry->get($userBuilder->getUser());

            $workflow->apply($userBuilder->getUser(), 'to_validate');

            $this->entityManagerInterface->persist($userBuilder->getUser());
            $this->entityManagerInterface->flush();

            return true;
        }

        return false;
    }
}
