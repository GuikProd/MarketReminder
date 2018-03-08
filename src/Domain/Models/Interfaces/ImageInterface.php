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

namespace App\Domain\Models\Interfaces;

/**
 * Interface ImageInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageInterface
{
    /**
     * @return int|null
     */
    public function getId():? int;

    /**
     * @return string
     */
    public function getCreationDate(): string;

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate(\DateTime $creationDate): void;

    /**
     * @return null|string
     */
    public function getModificationDate():? string;

    /**
     * @param \DateTime $modificationDate
     */
    public function setModificationDate(\DateTime $modificationDate): void;

    /**
     * @return null|string
     */
    public function getAlt():? string;

    /**
     * @param string $alt
     */
    public function setAlt(string $alt): void;

    /**
     * @return null|string
     */
    public function getUrl():? string;

    /**
     * @param string $url
     */
    public function setUrl(string $url): void;
}
