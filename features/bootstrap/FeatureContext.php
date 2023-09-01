<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Testwork\Tester\Result\TestResult;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo 
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 * 
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext extends MinkContext implements Context
{
    use \App\Service\TraitSlugify;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var Response|null
     */
    private $response;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->container = $kernel->getContainer();
    }

    /**
     * @When a demo scenario sends a request to :path
     */
    public function aDemoScenarioSendsARequestTo(string $path)
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
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
     * Take screen-shot when step fails. Works only with Selenium2Driver.
     *
     * @AfterStep
     *
     * @param AfterStepScope $scope
     * @throws \Behat\Mink\Exception\DriverException
     * @throws \Behat\Mink\Exception\UnsupportedDriverActionException
     */
    public function takeScreenshotAfterFailedStep(AfterStepScope $scope)
    {

        if (TestResult::FAILED === $scope->getTestResult()->getResultCode()) {

            $screenshotPath = $this->container->getParameter('kernel.project_dir')."/var/behat";

            $driver = $this->getSession()->getDriver();

            if (!is_dir($screenshotPath)) {
                if (!mkdir($screenshotPath, 0777, true) && !is_dir($screenshotPath)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $screenshotPath));
                }
            }

            // Remove space and special chars
            /** @var \Behat\Gherkin\Node\ScenarioNode $scenario */
            $scenario = current($scope->getFeature()->getScenarios());
            $scenarioTitle =  $this->slugify($scenario->getTitle());
            $stepText = $this->slugify($scope->getStep()->getText());

            if ($driver instanceof Selenium2Driver) {

                $filename = sprintf(
                    '%s_%s_%s.%s',
                    time(),
                    $scenarioTitle,
                    $stepText,
                    'png'
                );

                $this->saveScreenshot($filename, $screenshotPath);

                return;

            }

            $filename = sprintf(
                '%s_%s_%s.%s',
                time(),
                $scenarioTitle,
                $stepText,
                'html'
            );

            $fs = new \Symfony\Component\Filesystem\Filesystem();
            $fs->dumpFile("$screenshotPath/$filename", $driver->getHtml('html'));

        }
    }
}
