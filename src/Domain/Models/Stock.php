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

namespace App\Domain\Models;

use App\Domain\Models\Interfaces\StockInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Stock.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class Stock implements StockInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var array
     */
    public $status;

    /**
     * @var array
     */
    private $tags = [];

    /**
     * @var array
     */
    private $stockItems = [];

    /**
     * @var \DateTime
     */
    private $creationDate;

    /**
     * @var \DateTime
     */
    private $modificationDate;

    /**
     * @var UserInterface
     */
    private $owner;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        StockCreationDTOInterface $stockCreationDTO,
        UserInterface $owner
    ) {
        $this->id = Uuid::uuid4();
        $this->status = $stockCreationDTO->status;
        $this->tags = $stockCreationDTO->tags;
        $this->stockItems = new ArrayCollection();
        $this->creationDate = time();
        $this->owner = $owner;

        $this->addItems($stockCreationDTO->stockItems);
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCreationDate(): string
    {
        return $this->creationDate->format('d-m-Y h:i:s');
    }

    /**
     * @return string
     */
    public function getModificationDate(): ? string
    {
        return $this->modificationDate->format('d-m-Y h:i:s');
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus(): array
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function addItems(array $items): void
    {
        if (\is_array($items) && \count($items) == 0) {
            return;
        }

        foreach ($items as $item) {
            $this->stockItems[] = $item;

            $item->setStock($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): array
    {
        return $this->stockItems;
    }

    /**
     * @param Products $products
     */
    public function removeProduct(Products $products)
    {
        $this->products->removeElement($products);
    }
}
