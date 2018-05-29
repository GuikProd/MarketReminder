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

namespace App\Infra\GCP\DataCollector;

use App\Infra\GCP\DataCollector\Interfaces\GCPDataCollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

/**
 * Class GCPDataCollector.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class GCPDataCollector extends DataCollector implements DataCollectorInterface
{
    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        dump($this->getCasters());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        // TODO: Implement reset() method.
    }
}
