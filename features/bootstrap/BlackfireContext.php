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

use Blackfire\Probe;
use Blackfire\Client;
use Behat\Behat\Context\Context;

/**
 * Class BlackfireContext.
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class BlackfireContext implements Context
{
    /**
     * @var Probe
     */
    private $probe;

    /**
     * @var Client
     */
    private $client;

    /**
     * @BeforeScenario
     */
    public function iStartAProbe()
    {
        $this->client = new Client();

        $this->probe = $this->client->createProbe();
    }

    /**
     * @AfterScenario
     */
    public function iStopAProbe()
    {
        $this->client->endProbe($this->probe);
    }
}
