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

use App\Infra\GCP\CloudTranslation\Bridge\CloudTranslationBridge;
use App\Infra\GCP\CloudTranslation\Bridge\Interfaces\CloudTranslationBridgeInterface;
use Behat\Behat\Context\Context;

/**
 * Class CloudTranslationBridgeContext.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class CloudTranslationBridgeContext implements Context
{
    /**
     * @var CloudTranslationBridgeInterface
     */
    private $cloudTranslationBridge;

    /**
     * @var string
     */
    private $translatedContent;

    /**
     * @param string $credentialsFileName
     *
     * @Given I create a new CloudTranslationBridge using credentials :credentialsFilename
     */
    public function iCreateANewCloudTranslationBridgeUsingCredentials(string $credentialsFileName)
    {
        $this->cloudTranslationBridge = new CloudTranslationBridge(
            $credentialsFileName,
            __DIR__.'./../assets/_credentials'
        );
    }

    /**
     * @param string $value
     * @param string $locale
     *
     * @Then I want to translate a new entry :value using the following locale :locale
     */
    public function iWantToTranslateANewEntryUsingTheFollowingLocale(string $value, string $locale)
    {
        $this->translatedContent = $this->cloudTranslationBridge->getTranslateClient()->translate($value, ['target' => $locale])['text'];
    }

    /**
     * @Then The result should be defined.
     */
    public function theResultShouldBeDefined()
    {
        if (\is_null($this->translatedContent)) {
            throw new \LogicException(sprintf('The content should be translated !'));
        }
    }
}
