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
     * ImageInterface constructor.
     *
     * @param string $alt
     * @param string $filename
     * @param string $publicUrl
     */
    public function __construct(string $alt, string $filename, string $publicUrl);

    /**
     * @return int|null
     */
    public function getId():? int;

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime;

    /**
     * @return null|\DateTime
     */
    public function getModificationDate():? \DateTime;

    /**
     * @return null|string
     */
    public function getAlt():? string;

    /**
     * @return null|string
     */
    public function getPublicUrl():? string;

    /**
     * @return string
     */
    public function getFilename(): string;
}
