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

namespace App\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UserExist.
 *
 * @package App\Application\Validator
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class UserExist extends Constraint
{
    /**
     * The key used to translate the error message.
     *
     * @var string
     */
    public $message = 'user.exist';

    /**
     * Allow to decide if the user should exist or not.
     *
     * @var bool
     */
    public $exist = true;

    /**
     * @inheritdoc
     */
    public function validatedBy()
    {
        return UserExistValidator::class;
    }

    /**
     * @inheritdoc
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
