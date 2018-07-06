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

namespace App\Tests\Infra\GCP\CloudTranslation\Helper\Validator;

use App\Infra\GCP\CloudTranslation\Domain\Models\CloudTranslationItem;
use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationInterface;
use App\Infra\GCP\CloudTranslation\Helper\Validator\CloudTranslationValidator;
use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationValidatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudTranslationValidatorUnitTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationValidatorUnitTest extends TestCase
{
    /**
     * @var CloudTranslationInterface
     */
    private $cloudTranslation;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cloudTranslation = $this->createMock(CloudTranslationInterface::class);
    }

    public function testItImplements()
    {
        $cloudTranslationValidator = new CloudTranslationValidator();

        static::assertInstanceOf(
            CloudTranslationValidatorInterface::class,
            $cloudTranslationValidator
        );
    }

    /**
     * @dataProvider provideWrongContent
     *
     * @param array $defaultValue
     * @param array $newValues
     */
    public function testViolationsAreTriggered(
        array $defaultValue,
        array $newValues
    ) {
        $this->cloudTranslation->method('getItems')->willReturn($defaultValue);

        $cloudTranslationValidator = new CloudTranslationValidator();

        $errors = $cloudTranslationValidator->validate($this->cloudTranslation, $newValues);

        static::assertGreaterThan(0, $errors);
    }


    /**
     * @dataProvider provideRightContent
     *
     * @param array $defaultValue
     * @param array $newValues
     */
    public function testContentIsValid(
        array $defaultValue,
        array $newValues
    ) {
        $this->cloudTranslation->method('getItems')->willReturn($defaultValue);

        $cloudTranslationValidator = new CloudTranslationValidator();

        $errors = $cloudTranslationValidator->validate($this->cloudTranslation, $newValues);

        static::assertCount(0, $errors);
    }

    /**
     * @return \Generator
     */
    public function provideWrongContent()
    {
        yield array([
            new CloudTranslationItem([
            '_locale' => 'fr',
            'tag' => 'Hello',
            'channel' => 'messages',
            'key' => 'hello.home',
            'value' => 'Hello !'])
        ], ['home.text' => 'Hello World !']);
    }

    /**
     * @return \Generator
     */
    public function provideRightContent()
    {
        yield array([
            new CloudTranslationItem([
                '_locale' => 'fr',
                'tag' => 'Hello',
                'channel' => 'messages',
                'key' => 'home.text',
                'value' => 'Hello World !'])
        ], ['home.text' => 'Hello World !']);
        yield array([
            new CloudTranslationItem([
                '_locale' => 'fr',
                'tag' => 'Hello',
                'channel' => 'messages',
                'key' => 'home.btn',
                'value' => 'Hello World !'])
        ], ['home.btn' => 'Hello World !']);
    }
}
