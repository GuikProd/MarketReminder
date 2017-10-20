<?php

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Models;

/**
 * Class Products
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
    public function getId():? int
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
     * @return \DateTime
     */
    public function getEntryDate(): \DateTime
    {
        return $this->entryDate;
    }

    /**
     * @param \DateTime $entryDate
     */
    public function setEntryDate(\DateTime $entryDate)
    {
        $this->entryDate = $entryDate;
    }

    /**
     * @return \DateTime
     */
    public function getModificationDate():? \DateTime
    {
        return $this->modificationDate;
    }

    /**
     * @param \DateTime $modificationDate
     */
    public function setModificationDate(\DateTime $modificationDate)
    {
        $this->modificationDate = $modificationDate;
    }

    /**
     * @return \DateTime
     */
    public function getOutDate():? \DateTime
    {
        return $this->outDate;
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
     * @return \DateTime
     */
    public function getLimiteConsumptionDate(): \DateTime
    {
        return $this->limiteConsumptionDate;
    }

    /**
     * @param \DateTime $limiteConsumptionDate
     */
    public function setLimiteConsumptionDate(\DateTime $limiteConsumptionDate)
    {
        $this->limiteConsumptionDate = $limiteConsumptionDate;
    }

    /**
     * @return \DateTime
     */
    public function getLimiteUsageDate(): \DateTime
    {
        return $this->limiteUsageDate;
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
