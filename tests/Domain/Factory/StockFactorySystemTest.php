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

namespace App\Tests\Domain\Factory;

use App\Domain\Factory\Interfaces\StockFactoryInterface;
use App\Domain\Factory\StockFactory;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\StockCreationDTO;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class StockFactorySystemTest.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockFactorySystemTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var StockCreationDTOInterface|null
     */
    private $dto = null;

    /**
     * @var StockFactoryInterface|null
     */
    private $factory = null;

    /**
     * @var UserInterface|null
     */
    private $owner = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->dto = new StockCreationDTO('', '');
        $this->factory = new StockFactory();
        $this->owner = $this->createMock(UserInterface::class);
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItCreateFromDTO()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 200kB', 'StockFactory DTO creation memory usage');

        $this->assertBlackfire($configuration, function () {
            $this->factory->createFromUI($this->dto, $this->owner);
        });
    }

    /**
     * @group Blackfire
     *
     * @requires extension blackfire
     */
    public function testItCreateFromRawData()
    {
        $configuration = new Configuration();
        $configuration->assert('main.peak_memory < 10kB', 'StockFactory raw data creation memory usage');

        $this->assertBlackfire($configuration, function () {
            $this->factory->createFromData('', '', $this->owner);
        });
    }
}
