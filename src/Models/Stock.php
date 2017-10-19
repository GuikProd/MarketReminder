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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Stock
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class Stock
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $creationDate;

    /**
     * @var \DateTime
     */
    private $modificationDate;

    /**
     * @var string
     */
    private $status;

    /**
     * @var User
     */
    private $user;

    /**
     * @var ArrayCollection
     */
    private $products;

    /**
     * Stock constructor.
     */
    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->products = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId():? int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate(\DateTime $creationDate)
    {
        $this->creationDate = $creationDate;
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return ArrayCollection
     */
    public function getProducts(): ArrayCollection
    {
        return $this->products;
    }

    /**
     * @param Products $products
     */
    public function addProduct(Products $products)
    {
        $this->products[] = $products;
    }

    /**
     * @param Products $products
     */
    public function removeProduct(Products $products)
    {
        $this->products->removeElement($products);
    }
}
