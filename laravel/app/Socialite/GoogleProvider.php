<?php

namespace App\Socialite;

use Laravel\Socialite\Two\GoogleProvider as BaseGoogleProvider;

/**
 * Extiende el proveedor de Google de Socialite para:
 * - Usar el endpoint de tokens actualizado de Google
 * - Añadir logging detallado del intercambio de token
 */
class GoogleProvider extends BaseGoogleProvider
{
    protected function getTokenUrl(): string
    {
        return 'https://oauth2.googleapis.com/token';
    }

    public function getAccessTokenResponse($code): array
    {
        $fields = $this->getTokenFields($code);

        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers'     => ['Accept' => 'application/json'],
            'form_params' => $fields,
        ]);

        return json_decode($response->getBody(), true);
    }
}
