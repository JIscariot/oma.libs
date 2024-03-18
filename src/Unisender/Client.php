<?php

declare(strict_types=1);

namespace Libs\Unisender;

use GuzzleHttp\Client as BaseClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Throwable;

final class Client
{
    protected BaseClient $client;

    public function __construct(string $apiKey)
    {
        $this->client = new BaseClient([
            'base_uri' => 'https://api.unisender.com/ru/api/', 'query' => [
                'api_key' => $apiKey, 'format' => 'json',
            ],
        ]);
    }

    /**
     * Create and send an HTTP request.
     * @throws Throwable
     */
    private function request(string $method = 'GET', string $uri = '', array $options = []): object
    {
        try {
            $response = $this->client->request(
                $method, $uri, $options
            );
            $responseBody = json_decode($response->getBody()->getContents());

            if (
                property_exists($responseBody, 'code') &&
                property_exists($responseBody, 'error')
            ) {
                throw new ClientException($responseBody->error, $responseBody->code);
            }

            return $responseBody->result;
        } catch (ConnectException $e) {
            throw new ClientException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        } catch (RequestException $e) {
            throw new ClientException(
                $e->getResponse()->getReasonPhrase(),
                $e->getResponse()->getStatusCode(),
                $e
            );
        }
    }

    /**
     * @throws Throwable
     */
    public function subscribe(ContactData $data): int
    {
        $responseBody = $this->request('POST', 'subscribe', [
            'form_params' => [
                'overwrite' => 1,
                'double_optin' => 4,
                'fields' => [
                    'email' => $data->email,
                    'Name' => $data->name,
                ],
                'list_ids' => $data->getGetListsAsString()
            ],
        ]);

        return $responseBody->person_id;
    }

    /**
     * @throws Throwable
     */
    public function unsubscribe(ContactData $data): bool
    {
        $responseBody = $this->request('POST', 'unsubscribe', [
            'form_params' => [
                'contact' => $data->email,
                'contact_type' => 'email',
                'list_ids' => $data->getGetListsAsString()
            ],
        ]);

        return true;
    }

    /**
     * @throws Throwable
     */
    public function deleteContact(ContactData $data): bool
    {
        if (!$this->isContactExists($data)) {
            return false;
        }

        $responseBody = $this->request('POST', 'importContacts', [
            'form_params' => [
                'field_names' => [
                    'email', 'delete'
                ],
                'data' => [
                    [$data->email, 1]
                ]
            ],
        ]);

        return true;
    }

    /**
     * @throws Throwable
     */
    public function isContactExists(ContactData $data): bool
    {
        try {
            $responseBody = $this->request('POST', 'getContact', [
                'form_params' => [
                    'email' => $data->email
                ],
            ]);

            return true;
        } catch (ClientException $e) {
            if ($e->getCode() === 10404) {
                return false;
            }
            throw $e;
        }
    }
}