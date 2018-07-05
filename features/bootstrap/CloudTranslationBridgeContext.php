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
use App\Infra\GCP\CloudTranslation\Client\CloudTranslationClient;
use App\Infra\GCP\CloudTranslation\Client\Interfaces\CloudTranslationClientInterface;
use App\Infra\GCP\Loader\CredentialsLoader;
use App\Infra\GCP\Loader\Interfaces\LoaderInterface;
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
     * @var CloudTranslationClientInterface
     */
    private $cloudTranslationClient;

    /**
     * @var LoaderInterface
     */
    private $loader;

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
        $this->loader = new CredentialsLoader();

        $this->cloudTranslationBridge = new CloudTranslationBridge(
            $credentialsFileName,
            __DIR__.'/../assets/_credentials',
            $this->loader
        );
    }

    /**
     * @Then I create a new CloudTranslationClient
     */
    public function iCreateANewCloudTranslationClient()
    {
        $this->cloudTranslationClient = new CloudTranslationClient($this->cloudTranslationBridge);
    }

    /**
     * @param string $entry
     * @param string $locale
     *
     * @Then I want to translate a new entry :entry using the following locale :locale
     */
    public function iWantToTranslateANewEntryUsingTheFollowingLocale(string $entry, string $locale)
    {
        $this->translatedContent = $this->cloudTranslationClient->translateSingleEntry($entry, $locale);
    }

    /**
     * @param array $entries
     * @param string $locale
     *
     * @Then I want to translate a series of entry :entry using the following locale :locale
     */
    public function iWantToTranslateASeriesOfEntryUsingTheFollowingLocale(array $entries, string $locale)
    {
        $this->translatedContent = $this->cloudTranslationClient->translateArray($entries, $locale)['text'];
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
