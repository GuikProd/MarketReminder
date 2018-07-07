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

namespace App\Tests\UI\Form\Type\Stock;

use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationItemInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\UI\Form\Subscriber\Interfaces\StockItemCreationSubscriberInterface;
use App\UI\Form\Subscriber\StockItemCreationSubscriber;
use App\UI\Form\Type\Stock\StockItemCreationType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class StockItemCreationTypeUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockItemCreationTypeUnitTest extends TypeTestCase
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
     * @var StockItemCreationSubscriberInterface|null
     */
    private $stockItemCreationSubscriber = null;

    /**
     * {@inheritdoc}
     */
    protected function getExtensions()
    {
        $this->cloudTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $requestMock = $this->createMock(Request::class);

        $this->stockItemCreationSubscriber = new StockItemCreationSubscriber(
            $this->cloudTranslationRepository,
            $this->requestStack
        );

        $type = new StockItemCreationType($this->stockItemCreationSubscriber);

        $this->requestStack->method('getCurrentRequest')->willReturn($requestMock);
        $requestMock->method('getLocale')->willReturn('fr');

        return [new PreloadedExtension([$type], [])];
    }

    /**
     * @dataProvider provideItemType
     *
     * @param string $itemType
     */
    public function testItemCreationWithoutDate(string $itemType)
    {
        $cloudTranslationItemMock = $this->createMock(CloudTranslationItemInterface::class);

        $this->cloudTranslationRepository->method('getSingleEntry')->willReturn($cloudTranslationItemMock);
        $cloudTranslationItemMock->method('getValue')->willReturn($itemType);

        $form = $this->factory->create(StockItemCreationType::class);
        $form->submit([
            'name' => 'test',
            'status' => 'on',
            'quantity' => 0,
            'withoutTaxesPrice' => 0.0,
            'withTaxesPrice' => 0.0,
            'type' => $itemType
        ]);

        static::assertTrue($form->isSynchronized());
        static::assertTrue($form->isValid());
    }

    /**
     * @return \Generator
     */
    public function provideItemType()
    {
        yield array('Food');
        yield array('Nourriture');
    }
}
