<?php

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo 
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 * 
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var Response|null
     */
    private $response;

    /**
     * FeatureContext constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param string $path      The path to go.
     * @param string $method    The method linked to this request.
     *
     * @When i send a request to :path with method :method
     */
    public function iSendARequestToWithMethod(string $path, string $method)
    {
        $this->response = $this->kernel->handle(Request::create($path, $method));
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived()
    {
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }
    }

    /**
     * @param int $statusCode
     *
     * @throws Exception
     *
     * @Then the response status code should be :statusCode
     */
    public function theResponseStatusCodeShouldBe(int $statusCode)
    {
        if ($this->response->getStatusCode() !== $statusCode) {
            throw new \Exception(
                sprintf(
                    'Bad status code ! Found %d',
                    $this->response->getStatusCode()
                )
            );
        }
    }
}
