<?php

namespace App\Tests\Behat\Core;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use GuzzleHttp\Client;

trait AuthContextDI
{
    protected ?AuthContext $authContext;

    /** @BeforeScenario */
    public function getAuthContextBeforeScenario(BeforeScenarioScope $scope)
    {
        // Get the environment
        $environment = $scope->getEnvironment();

        // Get all the contexts you need in this context
        $this->authContext = $environment->getContext(AuthContext::class);
    }

    public function getLoggedInClient(): Client
    {
        return $this->authContext->getClient();
    }

}
