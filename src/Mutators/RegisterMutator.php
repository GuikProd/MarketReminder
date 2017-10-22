<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Mutators;

use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

/**
 * Class RegisterMutator
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class RegisterMutator implements MutationInterface
{
    public function register(Argument $argument)
    {

    }
}
