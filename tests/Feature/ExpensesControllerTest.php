<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Expenses;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ExpenseRegistered;
use Illuminate\Support\Facades\Log;

class ExpensesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        $this->followingRedirects();
    }

    /** @test */
    public function it_can_create_expense()
    {
        Notification::fake();

        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/expenses', [
            'description' => 'Test Expense',
            'date' => now()->toDateString(),
            'amount' => 100.00,
            'user_id' => $user->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('expenses', [
            'description' => 'Test Expense',
            'amount' => 100.00,
        ]);

        Notification::assertSentTo(
            [$user], ExpenseRegistered::class
        );
    }

    /** @test */
    public function it_can_show_an_expense()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $expense = Expenses::create([
            'description' => 'Test Expense',
            'date' => now()->toDateString(),
            'amount' => 100.00,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->getJson('/api/expenses/' . $expense->id);
        
        $response->assertStatus(200);
        $response->assertJson(['data' => ['description' => 'Test Expense']]);
    }

    /** @test */
    public function it_can_update_an_expense()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $expense = Expenses::create([
            'description' => 'Test Expense',
            'date' => now()->toDateString(),
            'amount' => 100.00,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->putJson('/api/expenses/' . $expense->id, [
            'description' => 'Updated Expense',
            'date' => now()->toDateString(),
            'amount' => 150.00,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('expenses', [
            'description' => 'Updated Expense',
            'amount' => 150.00
        ]);
    }

    /** @test */
    public function it_can_delete_an_expense()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $expense = Expenses::create([
            'description' => 'Test Expense',
            'date' => now()->toDateString(),
            'amount' => 100.00,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->deleteJson('/api/expenses/' . $expense->id);
        
        $response->assertStatus(204);
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }
}