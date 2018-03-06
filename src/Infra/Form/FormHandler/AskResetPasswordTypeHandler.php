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

namespace App\Infra\Form\FormHandler;

use App\Domain\UseCase\UserResetPassword\Model\UserResetPasswordToken;
use App\Infra\Form\FormHandler\Interfaces\AskResetPasswordTypeHandlerInterface;
use App\Infra\Helper\Security\TokenGeneratorHelper;
use App\Interactor\UserInteractor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AskResetPasswordTypeHandler
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordTypeHandler implements AskResetPasswordTypeHandlerInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * AskResetPasswordTypeHandler constructor.
     *
     * @param SessionInterface        $session
     * @param TranslatorInterface     $translator
     * @param EntityManagerInterface  $entityManager
     */
    public function __construct(
        SessionInterface $session,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager
    ) {
        $this->session = $session;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $askResetPasswordType): bool
    {
        if ($askResetPasswordType->isSubmitted() && $askResetPasswordType->isValid()) {

            $user = $this->entityManager->getRepository(UserInteractor::class)
                                        ->getUserByUsernameAndEmail(
                                            $askResetPasswordType->getData()->username,
                                            $askResetPasswordType->getData()->email
                                        );

            if (!$user) {
                $this->session->getFlashBag()
                              ->add(
                                  'failure',
                                  $this->translator->trans('user.not_found')
                              );

                return false;
            }

            $userResetPasswordToken = new UserResetPasswordToken(
                TokenGeneratorHelper::generateResetPasswordToken(
                    $askResetPasswordType->getData()->username,
                    $askResetPasswordType->getData()->email
                )
            );

            $user->askForPasswordReset($userResetPasswordToken);

            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
