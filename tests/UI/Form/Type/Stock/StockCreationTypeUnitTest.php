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

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;
use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationItemInterface;
use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\UI\Form\DataTransformer\Interfaces\StockCreationTagsTransformerInterface;
use App\UI\Form\DataTransformer\StockCreationTagsTransformer;
use App\UI\Form\Subscriber\Interfaces\StockItemCreationSubscriberInterface;
use App\UI\Form\Subscriber\StockItemCreationSubscriber;
use App\UI\Form\Type\Stock\StockCreationType;
use App\UI\Form\Type\Stock\StockItemCreationType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class StockCreationTypeUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockCreationTypeUnitTest extends TypeTestCase
{
    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $cloudTranslationRepository = null;

    /**
     * @var null|RequestStack
     */
    private $request = null;

    /**
     * @var StockItemCreationSubscriberInterface
     */
    private $stockItemSubscriber = null;

    /**
     * @var StockCreationTagsTransformerInterface
     */
    private $tagsTransformer = null;

    /**
     * {@inheritdoc}
     */
    protected function getExtensions()
    {
        $this->cloudTranslationRepository = $this->createMock(CloudTranslationRepositoryInterface::class);
        $this->request = $this->createMock(RequestStack::class);
        $requestMock = $this->createMock(Request::class);

        $this->stockItemSubscriber = new StockItemCreationSubscriber($this->cloudTranslationRepository, $this->request);
        $this->tagsTransformer = new StockCreationTagsTransformer();

        $stockCreationType = new StockCreationType($this->tagsTransformer);
        $stockItemCreationType = new StockItemCreationType($this->stockItemSubscriber);

        $this->request->method('getCurrentRequest')->willReturn($requestMock);
        $requestMock->method('getLocale')->willReturn('fr');

        return [new PreloadedExtension([$stockCreationType, $stockItemCreationType], [])];
    }

    public function testStockCreationWithoutItems()
    {
        $cloudTranslationItem = $this->createMock(CloudTranslationItemInterface::class);

        $this->cloudTranslationRepository->method('getSingleEntry')->willReturn($cloudTranslationItem);
        $cloudTranslationItem->method('getValue')->willReturn('');

        $form = $this->factory->create(StockCreationType::class);

        $form->submit([
            'title' => 'test',
            'status' => 'on',
            'tags' => 'toto, test, test_II',
            'stockItems' => []
        ]);

        static::assertTrue($form->isSynchronized());
        static::assertTrue($form->isValid());

        static::assertSame('test', $form->get('title')->getData());
        static::assertSame('on', $form->get('status')->getData());
        static::assertArrayNotHasKey('content', $form->get('tags')->getData());
        static::assertContains('test', $form->get('tags')->getData());
        static::assertInstanceOf(StockCreationDTOInterface::class, $form->getData());
    }

    /**
     * @dataProvider provideItemType
     *
     * @param string $itemType
     */
    public function testStockCreationWithItems(string $itemType)
    {
        $cloudTranslationItem = $this->createMock(CloudTranslationItemInterface::class);

        $this->cloudTranslationRepository->method('getSingleEntry')->willReturn($cloudTranslationItem);
        $cloudTranslationItem->method('getValue')->willReturn($itemType);

        $form = $this->factory->create(StockCreationType::class);

        $form->submit([
            'title' => 'test',
            'status' => 'on',
            'tags' => 'toto, test, test_II',
            'stockItems' => [
                0 => [
                    'name' => 'test',
                    'status' => 'on',
                    'quantity' => 0,
                    'withoutTaxesPrice' => 0.0,
                    'withTaxesPrice' => 0.0,
                    'type' => $itemType
                ]
            ]
        ]);

        static::assertTrue($form->isSynchronized());
        static::assertTrue($form->isValid());

        static::assertSame('test', $form->get('title')->getData());
        static::assertSame('on', $form->get('status')->getData());
        static::assertContains('toto', $form->get('tags')->getData());
        static::assertCount(1, $form->getData()->stockItems);
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
