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

use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockTagsDTOInterface;
use App\UI\Form\DataTransformer\Interfaces\StockCreationTagsTransformerInterface;
use App\UI\Form\DataTransformer\StockCreationTagsTransformer;
use App\UI\Form\Type\Stock\Interfaces\StockTagsTypeInterface;
use App\UI\Form\Type\Stock\StockTagsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class StockTagsTypeUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockTagsTypeUnitTest extends TypeTestCase
{
    /**
     * @var StockCreationTagsTransformerInterface
     */
    private $tagsTransformer = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->tagsTransformer = new StockCreationTagsTransformer();

        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtensions()
    {
        $type = new StockTagsType($this->tagsTransformer);

        return [new PreloadedExtension([$type], [])];
    }

    public function testItImplements()
    {
        $type = new StockTagsType($this->tagsTransformer);

        static::assertInstanceOf(
            StockTagsTypeInterface::class,
            $type
        );
        static::assertInstanceOf(
            AbstractType::class,
            $type
        );
    }

    public function testItHandleData()
    {
        $form = $this->factory->create(StockTagsType::class);

        $form->submit(['tags' => 'toto, hello, test']);

        static::assertTrue($form->isSynchronized());
        static::assertTrue($form->isValid());

        static::assertInstanceOf(StockTagsDTOInterface::class, $form->getData());
        static::assertContains('test', $form->getData()->tags);
    }
}
