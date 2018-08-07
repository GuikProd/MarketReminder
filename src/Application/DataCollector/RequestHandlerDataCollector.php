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

namespace App\Application\DataCollector;

use App\Application\DataCollector\Interfaces\RequestHandlerDataCollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class RequestHandlerDataCollector.
 *
 * @author  Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class RequestHandlerDataCollector extends DataCollector implements RequestHandlerDataCollectorInterface
{
    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['requestHandler'] = $request->attributes->get('_request_handler');
        $this->data['checked'] = $request->attributes->get('checked');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "requestHandler";
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->data = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestHandler(): ?string
    {
        return $this->data['requestHandler'];
    }

    /**
     * {@inheritdoc}
     */
    public function isChecked(): ?bool
    {
        return $this->data['checked'];
    }

    /**
     * {@inheritdoc}
     */
    public function isAMP(): bool
    {
        return true;
    }
}
