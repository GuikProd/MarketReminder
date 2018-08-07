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

namespace App\Domain\Models\Interfaces;

use Ramsey\Uuid\UuidInterface;

/**
 * Interface StockInterface.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
interface StockInterface
{
    /**
     * StockInterface constructor.
     *
     * @param string           $title
     * @param string           $status
     * @param UserInterface $owner
     * @param array           $tags
     * @param array           $stockItems
     */
    public function __construct(
        string $title,
        string $status,
        UserInterface $owner,
        array $tags = [],
        array $stockItems = []
    );

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param array $items
     */
    public function addItems(array $items): void;

    /**
     * @return array
     */
    public function getItems(): array;

    /**
     * @param array $tags
     */
    public function addTags(array $tags): void;

    /**
     * @return string
     */
    public function getCreationDate(): string;

    /**
     * @return null|string
     */
    public function getModificationDate(): ?string;
}
