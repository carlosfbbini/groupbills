<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Expense;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ExpenseReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_show_due_today_overdue_and_payment_history(): void
    {
        Carbon::setTestNow('2026-06-29');

        $user = User::factory()->create();
        $group = Group::query()->create(['name' => 'Grupo Carlos']);
        $company = Company::query()->create([
            'group_id' => $group->id,
            'name' => 'Empresa Beta',
        ]);

        Expense::query()->create([
            'company_id' => $company->id,
            'invoice' => 'TODAY',
            'installment' => 1,
            'amount' => 100,
            'due_date' => Carbon::today()->toDateString(),
            'status' => 'pending',
        ]);

        Expense::query()->create([
            'company_id' => $company->id,
            'invoice' => 'LATE',
            'installment' => 1,
            'amount' => 200,
            'due_date' => Carbon::today()->subDay()->toDateString(),
            'status' => 'pending',
        ]);

        Expense::query()->create([
            'company_id' => $company->id,
            'invoice' => 'PAID',
            'installment' => 1,
            'amount' => 300,
            'due_date' => Carbon::today()->subDays(3)->toDateString(),
            'status' => 'paid',
            'paid_at' => Carbon::today()->subDay()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertOk();
        $response->assertSee('TODAY');
        $response->assertSee('LATE');
        $response->assertSee('PAID');
    }
}
