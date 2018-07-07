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

namespace App\Tests\UI\Form\Subscriber;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\UI\Form\Subscriber\Interfaces\StockItemCreationSubscriberInterface;
use App\UI\Form\Subscriber\StockItemCreationSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class StockItemCreationSubscriberUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockItemCreationSubscriberUnitTest extends TestCase
{
    /**
     * @var CloudTranslationRepositoryInterface|null
     */
    private $cloudTranslationRepository = null;

    /**
     * @var RequestStack|null
     */
    private $requestStack = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);
    }

    public function testItImplements()
    {
        $subscriber = new StockItemCreationSubscriber(
            $this->cloudTranslationRepository,
            $this->requestStack
        );

        static::assertInstanceOf(
            StockItemCreationSubscriberInterface::class,
            $subscriber
        );
        static::assertInstanceOf(
            EventSubscriberInterface::class,
            $subscriber
        );
    }
}
