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

            // restrictOnDelete em payee_id e expense_category_id: uma conta
            // paga é dado histórico/financeiro — excluir o favorecido ou a
            // categoria não pode apagar silenciosamente essa referência.
            // Quem quiser excluir uma categoria precisa antes recategorizar
            // as despesas que a usam (mesmo espírito de proteção a dado
            // histórico já registrado em engineering-principles.md).
            $table->foreignId('payee_id')->constrained('payees')->restrictOnDelete();
            $table->foreignId('expense_category_id')->nullable()->constrained('expense_categories')->restrictOnDelete();

            $table->string('description');
            $table->decimal('amount', 10, 2);

            $table->date('due_date');

            // Nulo = ainda não paga. Junto com due_date, define o status
            // computado (Pendente/Pago/Atrasado) — ver Expense::status().
            // Não existe coluna "status": é sempre derivado destes dois
            // campos, para nunca ficar dessincronizado.
            $table->date('payment_date')->nullable();

            // Nome curto: mesma convenção de enums não-backed já usada no
            // projeto (PaymentStatus, OrderStatus) — gravado como ->name.
            $table->string('payment_method')->nullable();

            // 'none' por padrão — o motor de geração automática da próxima
            // ocorrência é construído na Fase 3, mas o campo já nasce aqui
            // pra não exigir outra migration depois.
            $table->string('recurrence_type')->default('none');

            // Auto-relacionamento: liga uma ocorrência gerada automaticamente
            // (Fase 3) de volta à despesa "modelo" que a originou. nullOnDelete:
            // excluir o modelo não deve arrastar as ocorrências já geradas.
            $table->foreignId('recurrence_parent_id')->nullable()->constrained('expenses')->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('due_date');
            $table->index('payment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};