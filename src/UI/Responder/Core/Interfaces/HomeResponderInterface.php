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

namespace App\UI\Responder\Core\Interfaces;

use Twig\Environment;

/**
 * Interface HomeResponderInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface HomeResponderInterface
{
    /**
     * HomeResponderInterface constructor.
     *
     * @param Environment  $twig
     */
    public function __construct(Environment $twig);
}
