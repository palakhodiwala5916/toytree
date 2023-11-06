<?php

namespace App\Tests\Behat\Core;

use App\DependencyInjection\Framework\EntityManagerDI;
use Behat\Behat\Context\Context;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Result;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\Assert;

/**
 * Base context class that initializes the guzzle client used by all other contexts
 */
abstract class BaseContext implements Context
{
    protected const BASE_URL_TEMPLATE = 'http://127.0.0.1:8000';
    protected const DEFAULT_URL = 'http://127.0.0.1:8000';

    protected ?\stdClass $responseData = null;

    private string $baseURL;
    private ?string $apiKey;

    use EntityManagerDI;

    /**
     * A preconfigured guzzle client to use in your test requests
     *
     * @var Client
     */
    protected Client $client;

    /**
     * @var string[]
     */
    protected array $defaultHeaders;

    public function __construct()
    {
        $this->apiKey = getenv('API_TEST_KEY');
        $this->baseURL = $this->buildBaseUrl();

        echo 'BaseContext: baseURL:' . $this->baseURL . "\n";
        $this->defaultHeaders = [
            'API-TEST-KEY' => $this->apiKey,
            'Content-Type' => 'application/json'
        ];
        $this->createNewGuzzleClient();
    }

    public function addDefaultHeader($header, $value)
    {
        $this->defaultHeaders[$header] = $value;
        $this->defaultHeaders['Content-Type'] = 'application/json';

        $this->createNewGuzzleClient();
    }

    public function addDefaultHeaders(array $headers)
    {
        $this->defaultHeaders = array_merge($this->defaultHeaders, $headers);
        $this->createNewGuzzleClient();
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    protected function createNewGuzzleClient()
    {
        $this->client = new Client([
            'base_uri' => $this->baseURL,
            'timeout' => 30.0,
            'verify' => false,
            'headers' => $this->defaultHeaders
        ]);
    }

    public function runApplicationCommand(string $cmd, array $args, ?string $expectedOutput = null): string
    {
        $options['json'] = [
            'command' => $cmd,
            'arguments' => $args,
        ];
        if ($expectedOutput) {
            $options['json']['expected_output'] = $expectedOutput;
        }

        try {
            $response = $this->getClient()->post('/tests/mock/run-command', $options);
        } catch (GuzzleException $e) {
            Assert::fail('ERROR: ' . $e->getMessage());
        }
        Assert::assertEquals(200, $response->getStatusCode(), 'Command failed successfully');

        return $response->getBody()->getContents();
    }

    protected function executeQuery(string $sql, array $params = []): Result
    {
        try {
            return $this->entityManager->getConnection()
                ->prepare($sql)
                ->executeQuery($params);
        } catch (Exception $e) {
            Assert::fail('[ExecuteQuery] ERROR: ' . $e->getMessage());
        }
    }

    private function detectBranchName(): string
    {
        return trim(implode('/', array_slice(explode('/', file_get_contents(__DIR__ . '/../../../.git/HEAD')), 2)));
    }

    private function buildBaseUrl(): string
    {
        $branch = $this->detectBranchName();
        if ($branch == 'vlad') {
            return self::DEFAULT_URL;
        }
        if (empty($branch)) {
            return self::DEFAULT_URL;
        }

        return str_replace('{branch_name}', $branch, self::BASE_URL_TEMPLATE);
    }
}
