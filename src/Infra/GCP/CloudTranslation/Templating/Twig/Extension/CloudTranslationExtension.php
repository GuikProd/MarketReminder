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

namespace App\Infra\GCP\CloudTranslation\Templating\Twig\Extension;

use App\Infra\GCP\CloudTranslation\Domain\Repository\Interfaces\CloudTranslationRepositoryInterface;
use App\Infra\GCP\CloudTranslation\Templating\Twig\Extension\Interfaces\CloudTranslationExtensionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class CloudTranslationExtension.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationExtension extends AbstractExtension implements CloudTranslationExtensionInterface
{
    /**
     * @var CloudTranslationRepositoryInterface
     */
    private $cloudTranslationRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(CloudTranslationRepositoryInterface $cloudTranslationRepository)
    {
        $this->cloudTranslationRepository = $cloudTranslationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('cloudTranslate', [$this, 'cloudTranslate'])
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function cloudTranslate(string $key, string $filename = 'messages', string $locale = 'fr'): string
    {
        return $this->cloudTranslationRepository->getSingleEntry($filename.'.'.$locale.'.yaml', $locale, $key)->getValue();
    }
}
