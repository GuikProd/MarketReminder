<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Repository;

use App\Models\Stock;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class StockRepositoryTest
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class StockRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::bootKernel();

        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testRepositoryExist()
    {
        static::assertInstanceOf(
            StockRepository::class,
            $this->entityManager->getRepository(Stock::class)
        );
    }
}
