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

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class FeatureContext.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class FeatureContext extends MinkContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * FeatureContext constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}
