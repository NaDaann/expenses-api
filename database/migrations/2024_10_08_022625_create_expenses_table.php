<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('description', 191)->nullable(); // ---- O tamanho máximo de uma descrição de despesa foi definido como 191. Foi especificado apenas que deveria existir um máximo, mas não foi especificado tamanho mínimo / se é obrigatório.
            $table->date('date');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade'); // ---- Adicionado a chave estrangeira para a tabela de usuários. 
            // Se um usuário for deletado, todas as despesas relacionadas a ele também serão deletadas.
            // Se um usuário for atualizado, todas as despesas relacionadas a ele também serão atualizadas.

            $table->decimal('amount', 18, 2); // ---- Não foi especificado o valor máximo das despesas de uma empresa, então coloquei 18 casas.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
