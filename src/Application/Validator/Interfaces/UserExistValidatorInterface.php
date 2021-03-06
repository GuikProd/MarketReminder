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

namespace App\Application\Validator\Interfaces;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interface UserExistValidatorInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface UserExistValidatorInterface
{
    /**
     * UserExistValidatorInterface constructor.
     *
     * @param TranslatorInterface      $translator
     * @param UserRepositoryInterface  $userRepository
     */
    public function __construct(
        TranslatorInterface $translator,
        UserRepositoryInterface $userRepository
    );
}
