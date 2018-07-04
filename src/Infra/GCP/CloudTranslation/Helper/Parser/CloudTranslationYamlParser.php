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

namespace App\Infra\GCP\CloudTranslation\Helper\Parser;

use App\Infra\GCP\CloudTranslation\Helper\Parser\Interfaces\CloudTranslationYamlParserInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CloudTranslationYamlParser.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationYamlParser implements CloudTranslationYamlParserInterface
{
    /**
     * @var array
     */
    private $keys = [];

    /**
     * @var array
     */
    private $values = [];

    /**
     * {@inheritdoc}
     */
    public function parseYaml(string $folder, string $filename): void
    {
        $content = Yaml::parse(
            file_get_contents($folder.'/'.$filename.'.yaml')
        );

        foreach ($content as $item => $value) {
            $this->keys[] = $item;
            $this->values[] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function getFinalArray(): array
    {
        return array_combine($this->keys, $this->values);
    }
}
