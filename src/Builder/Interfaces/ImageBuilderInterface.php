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

namespace App\Builder\Interfaces;

use App\Models\Interfaces\UserInterface;
use App\Models\Interfaces\ImageInterface;

/**
 * Interface ImageBuilderInterface.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ImageBuilderInterface
{
    /**
     * @return ImageBuilderInterface
     */
    public function createImage(): self;

    /**
     * @param \DateTime $creationDate
     *
     * @return ImageBuilderInterface
     */
    public function withCreationDate(\DateTime $creationDate): self;

    /**
     * @param \DateTime $modificationDate
     *
     * @return ImageBuilderInterface
     */
    public function withModificationDate(\DateTime $modificationDate): self;

    /**
     * @param string $alt
     *
     * @return ImageBuilderInterface
     */
    public function withAlt(string $alt): self;

    /**
     * @param string $publicUrl
     *
     * @return ImageBuilderInterface
     */
    public function withPublicUrl(string $publicUrl): self;

    /**
     * @param UserInterface $user
     *
     * @return ImageBuilderInterface
     */
    public function withUser(UserInterface $user): self;

    /**
     * @return ImageInterface
     */
    public function getImage(): ImageInterface;
}
