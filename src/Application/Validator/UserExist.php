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

namespace App\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UserExist.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class UserExist extends Constraint
{
    /**
     * @var string
     */
    public $message = 'user.exist';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return UserExistValidator::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
