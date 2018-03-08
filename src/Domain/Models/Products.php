<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Models;

/**
 * Class Products.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class Products
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $label;

    /**
     * @var \DateTime
     */
    private $entryDate;

    /**
     * @var \DateTime
     */
    private $modificationDate;

    /**
     * @var \DateTime
     */
    private $outDate;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $limiteConsumptionDate;

    /**
     * @var \DateTime
     */
    private $limiteUsageDate;

    /**
     * @var Stock
     */
    private $stock;

    /**
     * Products constructor.
     */
    public function __construct()
    {
        $this->entryDate = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): ? int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getEntryDate(): string
    {
        return $this->entryDate->format('d-m-Y h:i:s');
    }

    /**
     * @param \DateTime $entryDate
     */
    public function setEntryDate(\DateTime $entryDate)
    {
        $this->entryDate = $entryDate;
    }

    /**
     * @return string
     */
    public function getModificationDate(): ? string
    {
        return $this->modificationDate->format('d-m-Y h:i:s');
    }

    /**
     * @param \DateTime $modificationDate
     */
    public function setModificationDate(\DateTime $modificationDate)
    {
        $this->modificationDate = $modificationDate;
    }

    /**
     * @return string
     */
    public function getOutDate(): ? string
    {
        return $this->outDate->format('d-m-Y h:i:s');
    }

    /**
     * @param \DateTime $outDate
     */
    public function setOutDate(\DateTime $outDate)
    {
        $this->outDate = $outDate;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getLimiteConsumptionDate(): ? string
    {
        return $this->limiteConsumptionDate->format('d-m-Y h:i:s');
    }

    /**
     * @param \DateTime $limiteConsumptionDate
     */
    public function setLimiteConsumptionDate(\DateTime $limiteConsumptionDate)
    {
        $this->limiteConsumptionDate = $limiteConsumptionDate;
    }

    /**
     * @return string
     */
    public function getLimiteUsageDate(): ? string
    {
        return $this->limiteUsageDate->format('d-m-Y h:i:s');
    }

    /**
     * @param \DateTime $limiteUsageDate
     */
    public function setLimiteUsageDate(\DateTime $limiteUsageDate)
    {
        $this->limiteUsageDate = $limiteUsageDate;
    }

    /**
     * @return Stock
     */
    public function getStock(): Stock
    {
        return $this->stock;
    }

    /**
     * @param Stock $stock
     */
    public function setStock(Stock $stock)
    {
        $this->stock = $stock;
    }
}
