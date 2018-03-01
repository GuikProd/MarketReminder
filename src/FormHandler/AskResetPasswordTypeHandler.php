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

use App\FormHandler\Interfaces\AskResetPasswordTypeHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class AskResetPasswordTypeHandler
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class AskResetPasswordTypeHandler implements AskResetPasswordTypeHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * AskResetPasswordTypeHandler constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $askResetPasswordType): bool
    {
        if ($askResetPasswordType->isSubmitted() && $askResetPasswordType->isValid()) {


            $this->entityManager->persist($askResetPasswordType->getData());
            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
