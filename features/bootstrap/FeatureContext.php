<?php

require_once 'vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\HttpKernel\Client;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var Silex\Application
     */
    private static $app;

    /**
     * @var Symfony\Component\HttpKernel\Client
     */
    private static $client;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeFeature
     */
    public static function bootstrap()
    {
        require_once __DIR__.'/../../vendor/autoload.php';

        Dotenv::load(__DIR__.'/../../');
        self::$app = require __DIR__.'/../../app/app.php';

        require_once __DIR__.'/../../app/routes.php';
        self::$client = new Client(self::$app);
    }

    /**
     * @BeforeScenario
     */
    public static function setUp()
    {
        self::$app['db']->executeQuery('TRUNCATE issues');
    }

    /**
     * @Given there are issues:
     */
    public function thereAreIssues(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            self::$app['db']->insert('issues', $data);
        }
    }
    
    /**
     * @When I go to :arg1
     */
    public function iGoTo($uri)
    {
        self::$client->request('GET', $uri);
    }

    /**
     * @Then I should see :arg1 issues
     */
    public function iShouldSeeIssues($count)
    {
        $issues = self::$client->getCrawler()->filter('ul li');

        assertEquals($count, count($issues));
    }

    /**
     * @Then I should see :arg1 issue
     */
    public function iShouldSeeIssue($count)
    {
        $this->iShouldSeeIssues($count);
    }

    /**
     * @Given there are no issues
     */
    public function thereAreNoIssues()
    {
    }
}
