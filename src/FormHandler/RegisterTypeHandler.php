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

use Symfony\Component\Workflow\Workflow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use App\Builder\Interfaces\UserBuilderInterface;
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
     * @var Workflow
     */
    private $workflowRegistry;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoderInterface;

    /**
     * RegisterTypeHandler constructor.
     *
     * @param Workflow $workflowRegistry
     * @param EntityManagerInterface $entityManagerInterface
     * @param UserPasswordEncoderInterface $userPasswordEncoderInterface
     */
    public function __construct(
        Workflow $workflowRegistry,
        EntityManagerInterface $entityManagerInterface,
        UserPasswordEncoderInterface $userPasswordEncoderInterface
    ) {
        $this->workflowRegistry = $workflowRegistry;
        $this->entityManagerInterface = $entityManagerInterface;
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $registerForm, UserBuilderInterface $userBuilder): bool
    {
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

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
            ;

            $this->workflowRegistry->apply($userBuilder->getUser(), 'to_validate');

            $this->entityManagerInterface->persist($userBuilder->getUser());
            $this->entityManagerInterface->flush();

            return true;
        }

        return false;
    }
}
