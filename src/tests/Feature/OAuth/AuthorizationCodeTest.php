<?php

declare(strict_types=1);

namespace Tests\Feature\OAuth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('oauth')]
class AuthorizationCodeTest extends TestCase
{
    use RefreshDatabase;

    private function createPublicClient(string $redirectUri = 'http://localhost:3000/callback'): \Laravel\Passport\Client
    {
        /** @var ClientRepository $clientRepository */
        $clientRepository = app(ClientRepository::class);

        return $clientRepository->createAuthorizationCodeGrantClient(
            name: 'テスト用クライアント',
            redirectUris: [$redirectUri],
            confidential: false,
        );
    }

    #[Test]
    public function 未認証ユーザーは認可エンドポイントからログインにリダイレクトされる(): void
    {
        $client = $this->createPublicClient();

        $response = $this->get('/oauth/authorize?' . http_build_query([
            'client_id' => $client->getKey(),
            'redirect_uri' => 'http://localhost:3000/callback',
            'response_type' => 'code',
            'scope' => '',
            'code_challenge' => 'E9Melhoa2OwvFrEMTJguCHaoeK1t8URWbuGJSstw-cM',
            'code_challenge_method' => 'S256',
        ]));

        $response->assertRedirect('/login');
    }

    #[Test]
    public function 認証済みユーザーに認可画面が表示される(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $client = $this->createPublicClient();

        $response = $this->actingAs($user)->get('/oauth/authorize?' . http_build_query([
            'client_id' => $client->getKey(),
            'redirect_uri' => 'http://localhost:3000/callback',
            'response_type' => 'code',
            'scope' => '',
            'code_challenge' => 'E9Melhoa2OwvFrEMTJguCHaoeK1t8URWbuGJSstw-cM',
            'code_challenge_method' => 'S256',
        ]));

        $response->assertOk();
    }

    #[Test]
    public function 不正なclient_idで認可リクエストが失敗する(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/oauth/authorize?' . http_build_query([
            'client_id' => '00000000-0000-0000-0000-000000000000',
            'redirect_uri' => 'http://localhost:3000/callback',
            'response_type' => 'code',
            'scope' => '',
        ]));

        $response->assertStatus(401);
    }

    #[Test]
    public function 不正なredirect_uriで認可リクエストが失敗する(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $client = $this->createPublicClient();

        $response = $this->actingAs($user)->get('/oauth/authorize?' . http_build_query([
            'client_id' => $client->getKey(),
            'redirect_uri' => 'http://evil.example.com/callback',
            'response_type' => 'code',
            'scope' => '',
            'code_challenge' => 'E9Melhoa2OwvFrEMTJguCHaoeK1t8URWbuGJSstw-cM',
            'code_challenge_method' => 'S256',
        ]));

        $response->assertStatus(401);
    }

    #[Test]
    public function 公開クライアントはPKCEなしで認可リクエストが拒否される(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $client = $this->createPublicClient();

        $response = $this->actingAs($user)->get('/oauth/authorize?' . http_build_query([
            'client_id' => $client->getKey(),
            'redirect_uri' => 'http://localhost:3000/callback',
            'response_type' => 'code',
            'scope' => '',
        ]));

        // PKCEなしの公開クライアントはエラーになる
        $response->assertStatus(400);
    }
}
