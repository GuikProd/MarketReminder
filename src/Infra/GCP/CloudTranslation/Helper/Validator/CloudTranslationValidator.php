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

namespace App\Infra\GCP\CloudTranslation\Helper\Validator;

use App\Infra\GCP\CloudTranslation\Domain\Models\Interfaces\CloudTranslationInterface;
use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationValidatorInterface;
use App\Infra\GCP\CloudTranslation\Helper\Validator\Interfaces\CloudTranslationViolationListInterface;

/**
 * Class CloudTranslationValidator.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationValidator implements CloudTranslationValidatorInterface
{
    /**
     * @var CloudTranslationViolationListInterface[]
     */
    private $violations = [];

    /**
     * {@inheritdoc}
     */
    public function validate(CloudTranslationInterface $cloudTranslationItem, array $newValues): array
    {
        $translationKey = [];
        $translationContent = [];
        $toCheckKey = [];
        $toCheckContent = [];

        foreach ($cloudTranslationItem->getItems() as $item => $value) {
            $translationKey[] = $value->getKey();
            $translationContent[] = $value->getValue();
        }

        foreach ($newValues as $item => $value) {
            $toCheckKey[] = $item;
            $toCheckContent[] = $value;
        }

        $finalArray = array_combine($translationKey, $translationContent);
        $finalCheckArray = array_combine($toCheckKey, $toCheckContent);

        $differences = array_diff($finalArray, $finalCheckArray);

        if (\count($differences) > 0) {
            foreach ($differences as $difference) {
                $this->violations[] = new CloudTranslationViolation(
                    'A difference has been found !',
                    sprintf('The value "%s" seem to be absent from the cache content !', $difference)
                );
            }
        }

        return $this->violations;
    }
}
