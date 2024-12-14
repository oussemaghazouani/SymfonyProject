<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ReCaptchaService
{
    private HttpClientInterface $httpClient;
    private string $secretKey;

    public function __construct(HttpClientInterface $httpClient, string $secretKey)
    {
        $this->httpClient = $httpClient;
        $this->secretKey = $secretKey;
    }

    public function verify(string $recaptchaResponse, ?string $remoteIp = null): bool
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        // Prepare parameters for the request
        $parameters = [
            'secret' => $this->secretKey,
            'response' => $recaptchaResponse,
        ];

        if ($remoteIp) {
            $parameters['remoteip'] = $remoteIp;
        }

        // Send POST request to Google's reCAPTCHA verification API
        $response = $this->httpClient->request('POST', $url, [
            'body' => $parameters,
        ]);

        // Decode the JSON response
        $data = $response->toArray();

        // Return true if the verification was successful
        return isset($data['success']) && $data['success'] === true;
    }
}
