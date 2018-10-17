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

namespace App\Tests\UI\Presenter;

use App\UI\Presenter\Interfaces\PresenterInterface;
use App\UI\Presenter\Presenter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class PresenterUnitTest.
 *
 * @package App\Tests\UI\Presenter
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class PresenterUnitTest extends TestCase
{
    /**
     * @var TranslatorInterface|null
     */
    private $translator = null;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
    }

    public function testItImplements()
    {
        $presenter = new Presenter($this->translator);

        static::assertInstanceOf(PresenterInterface::class, $presenter);
    }

    /**
     * @dataProvider provideContent
     *
     * @param string $locale
     * @param array $content
     * @param string $value
     *
     * @return void
     */
    public function testItTranslateContent(
        string $locale,
        array $content,
        string $value
    ): void {

        $presenter = new Presenter($this->translator);

        $presenter->prepareOptions([
            '_locale' => $locale,
            'content' => [
                'home.text' => $content
            ]
        ]);

        static::assertSame($value, $presenter->getContent()['home.text']['value']);
    }

    /**
     * @return \Generator
     */
    public function provideContent()
    {
        yield array('fr', ['channel' => 'messages', 'key' => 'home.text'], 'Hello World !');
    }
}
