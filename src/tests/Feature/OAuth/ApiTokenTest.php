<?php

declare(strict_types=1);

namespace Tests\Feature\OAuth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('oauth')]
class ApiTokenTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function トークン認証でユーザー情報を取得できる(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'トークンテストユーザー',
            'email' => 'token@example.com',
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertOk();
        $response->assertJson([
            'name' => 'トークンテストユーザー',
            'email' => 'token@example.com',
        ]);
    }

    #[Test]
    public function トークンなしでAPIリクエストすると401が返る(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertUnauthorized();
    }
}
