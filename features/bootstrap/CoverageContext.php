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

use Behat\Behat\Context\Context;
use SebastianBergmann\CodeCoverage\Filter;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;

/**
 * Class CoverageContext.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class CoverageContext implements Context
{
    /**
     * @var CodeCoverage
     */
    private static $coverage;

    /**
     * @BeforeSuite
     */
    public static function setup()
    {
        $filter = new Filter();
        $filter->addDirectoryToWhitelist(__DIR__.'/../../src');
        self::$coverage = new CodeCoverage(null, $filter);
    }

    /** @AfterSuite */
    public static function tearDown()
    {
        $writer = new Facade();
        $writer->process(
            self::$coverage,
            __DIR__.'/../../coverage/behat'
        );
    }

    /**
     * @param BeforeScenarioScope $scope
     *
     * @BeforeScenario
     */
    public function startCoverage(BeforeScenarioScope $scope)
    {
        self::$coverage->start($this->getCoverageKeyFromScope($scope));
    }

    /**
     * @AfterScenario
     */
    public function stopCoverage()
    {
        self::$coverage->stop();
    }

    /**
     * @param BeforeScenarioScope $scope
     *
     * @return string
     */
    private function getCoverageKeyFromScope(BeforeScenarioScope $scope)
    {
        $name = $scope->getFeature()->getTitle().'::'.$scope->getScenario()->getTitle();

        return $name;
    }
}
