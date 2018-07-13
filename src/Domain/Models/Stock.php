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
use App\Domain\Models\Interfaces\StockItemsInterface;
use App\Domain\Models\Interfaces\UserInterface;
use App\Domain\UseCase\Dashboard\StockCreation\DTO\Interfaces\StockCreationDTOInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Stock.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class Stock implements StockInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    public $status;

    /**
     * @var array
     */
    public $currentStatus;

    /**
     * @var array
     */
    private $tags = [];

    /**
     * @var StockItemsInterface[]
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
        UserInterface $owner,
        array $stockItems = []
    ) {
        $this->id = Uuid::uuid4();
        $this->title = $stockCreationDTO->title;
        $this->status = $stockCreationDTO->status;
        $this->creationDate = time();
        $this->owner = $owner;

        $this->addTags($stockCreationDTO->tags->tags);

        \count($stockItems) > 0
            ? $this->addItems($stockItems)
            : $this->stockItems = $stockItems;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle(): string
    {
        return $this->title;
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
    public function getStatus(): string
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
     * {@inheritdoc}
     */
    public function addTags(array $tags): void
    {
        foreach ($tags as $tag) {
            $this->tags[] = $tag;
        }
    }
}
