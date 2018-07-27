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

namespace App\Infra\Doctrine\Repository;

use App\Domain\Models\Interfaces\StockInterface;
use App\Domain\Models\Stock;
use App\Domain\Repository\Interfaces\StockRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class StockRepository.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class StockRepository extends ServiceEntityRepository implements StockRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    /**
     * {@inheritdoc}
     */
    public function save(StockInterface $stock): void
    {
        $this->_em->persist($stock);
        $this->_em->flush();
    }
}
