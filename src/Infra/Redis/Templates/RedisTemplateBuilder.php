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

namespace App\Infra\Redis\Templates;

use App\Infra\Redis\Templates\Interfaces\RedisTemplateBuilderInterface;

/**
 * Class RedisTemplateBuilder
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class RedisTemplateBuilder implements RedisTemplateBuilderInterface
{
    /**
     * @var string
     */
    private $templateFolder;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $templateFolder)
    {
        $this->templateFolder = $templateFolder;
    }
}
