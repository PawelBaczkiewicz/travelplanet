<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Infrastructure\Api;

use GuzzleHttp\Client;
use Modules\Shared\Application\DTOs\RequestProductInstallmentsDTO;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Config;
use Modules\Shared\Infrastructure\Facades\Log;

class ApiClient
{
    public function __construct(private Client $client) {}

    public function getDomain(): string
    {
        return Config::get('services.travelplanet_api.domain');
    }

    public function getApiUsername(): string
    {
        return Config::get('services.travelplanet_api.username');
    }

    public function getApiPassword(): string
    {
        return Config::get('services.travelplanet_api.password');
    }

    public function getApiVersion(): string
    {
        return Config::get('services.travelplanet_api.version');
    }

    public function getApiTimeout(): float
    {
        return floatval(Config::get('services.travelplanet_api.timeout'));
    }

    /**
     * @throws \Exception
     */
    public function login(): string
    {
        $credentials = [
            'username' => $this->getApiUsername(),
            'password' => $this->getApiPassword()
        ];

        Log::logDebug('Login to API: entry', $credentials);

        $response = $this->client->post($this->getApiAuthorizeUrl(), [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query' => $credentials,
            'timeout' => $this->getApiTimeout(),
        ]);

        Log::logDebug('Login to API: success');

        $responseBody = json_decode($response->getBody()->getContents(), true);

        return $responseBody['token'];
    }

    public function fetchProductData(RequestProductInstallmentsDTO $requestProductInstallmentsDTO): array
    {
        $bearerToken = $this->login();

        $response = $this->client->get($this->getApiUrl(), [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $bearerToken
            ],
            'query' => $requestProductInstallmentsDTO->toArray(),
            'timeout' => $this->getApiTimeout()
        ]);

        $validatedResponse = $this->validateApiResponse($response);

        if ($validatedResponse === null) {
            throw new \Exception('Wrong Api response data structure');
        }

        return $validatedResponse;
    }

    private function validateApiResponse(ResponseInterface $response): ?array
    {
        $responseBody = json_decode($response->getBody()->getContents(), true);

        if (!empty($responseBody['data'] ?? null)) {

            foreach ($responseBody['data'] as $installment) {
                if (
                    empty($installment['amount'] ?? null) ||
                    empty($installment['currency'] ?? null) ||
                    empty($installment['dueDate'] ?? null)
                ) {
                    return null;
                }
            }
        }

        return $responseBody['data'];
    }

    private function getApiUrl(): string
    {
        return "{$this->getDomain()}/api/{$this->getApiVersion()}/payment-schedule";
    }

    private function getApiAuthorizeUrl(): string
    {
        return "{$this->getDomain()}/api/authorize";
    }
}
