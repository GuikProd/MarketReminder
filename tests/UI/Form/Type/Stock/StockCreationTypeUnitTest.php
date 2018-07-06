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

use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockCreationDTO;
use App\UI\Form\DataTransformer\Interfaces\StockCreationTagsTransformerInterface;
use App\UI\Form\DataTransformer\StockCreationTagsTransformer;
use App\UI\Form\Type\Stock\StockCreationType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class StockCreationTypeUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockCreationTypeUnitTest extends TypeTestCase
{
    /**
     * @var StockCreationTagsTransformerInterface
     */
    private $tagsTransformer = null;

    /**
     * {@inheritdoc}
     */
    protected function getExtensions()
    {
        $this->tagsTransformer = new StockCreationTagsTransformer();

        $stockCreationType = new StockCreationType($this->tagsTransformer);

        return [new PreloadedExtension([$stockCreationType], [])];
    }

    public function testWrongDataSubmission()
    {
        $entries = new StockCreationDTO('', '', []);

        $form = $this->factory->create(StockCreationType::class);

        $form->submit($entries);

        static::assertTrue($form->isSynchronized());
        static::assertTrue($form->isValid());

        static::assertSame('', $form->get('title')->getData());
        static::assertSame('', $form->get('status')->getData());
        static::assertArrayNotHasKey('content', $form->get('tags')->getData());
    }
}
