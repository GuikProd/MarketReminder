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
    public $currentStatus = [];

    /**
     * @var array
     */
    private $tags = [];

    /**
     * @var StockItemsInterface[]
     */
    private $stockItems = [];

    /**
     * @var \DateTimeInterface
     */
    private $creationDate;

    /**
     * @var \DateTimeInterface
     */
    private $modificationDate;

    /**
     * @var UserInterface
     */
    private $owner;

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function __construct(
        string $title,
        string $status,
        UserInterface $owner,
        array $tags = [],
        array $stockItems = []
    ) {
        $this->id = Uuid::uuid1();
        $this->title = $title;
        $this->status = $status;
        $this->creationDate = new \DateTimeImmutable();
        $this->owner = $owner;

        $this->addTags($tags);

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
    public function getModificationDate(): ?string
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
        if (\is_array($items) && \count($items) == 0) { return; }

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
