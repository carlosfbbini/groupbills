<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('invoice');
            $table->unsignedInteger('installment')->default(1);
            $table->decimal('amount', 12, 2);
            $table->date('due_date');
            $table->enum('status', ['paid', 'pending'])->default('pending');
            $table->date('paid_at')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'invoice', 'installment'], 'expenses_company_id_invoice_installment_unique');

            $table->index(['status', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
