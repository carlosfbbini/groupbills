<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_group_company_and_expense(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('groups.store'), ['name' => 'Grupo Carlos'])
            ->assertRedirect(route('groups.index'));

        $groupId = (int) \DB::table('groups')->value('id');

        $this->actingAs($user)
            ->post(route('companies.store'), [
                'group_id' => $groupId,
                'name' => 'Empresa Alpha',
            ])->assertRedirect(route('companies.index'));

        $companyId = (int) \DB::table('companies')->value('id');

        $this->actingAs($user)
            ->post(route('expenses.store'), [
                'company_id' => $companyId,
                'invoice' => 'FAT-001',
                'installment' => 1,
                'amount' => 1500.5,
                'due_date' => now()->toDateString(),
                'status' => 'pending',
            ])->assertRedirect(route('expenses.index'));

        $this->assertDatabaseHas('expenses', [
            'invoice' => 'FAT-001',
            'status' => 'pending',
        ]);
    }

    public function test_guest_must_authenticate_to_access_reports(): void
    {
        $this->get(route('reports.index'))
            ->assertRedirect(route('login'));
    }
}
