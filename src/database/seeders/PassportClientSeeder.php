<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;

class PassportClientSeeder extends Seeder
{
    public function run(): void
    {
        $clientRepository = app(ClientRepository::class);

        // 開発用 Authorization Code Grant クライアント
        $client = $clientRepository->createAuthorizationCodeGrantClient(
            name: '開発用SPAクライアント',
            redirectUris: ['http://localhost:3000/callback'],
            confidential: false,
        );

        if (app()->environment('local', 'testing')) {
            $this->command->info("Client ID: {$client->getKey()}");
            if ($client->getAttribute('secret')) {
                $this->command->info("Client Secret: {$client->getAttribute('secret')}");
            }
        }
    }
}
