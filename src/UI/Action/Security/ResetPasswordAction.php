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

namespace App\UI\Action\Security;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Security\Interfaces\ResetPasswordActionInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class ResetPasswordAction.
 * 
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ResetPasswordAction implements ResetPasswordActionInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
}
