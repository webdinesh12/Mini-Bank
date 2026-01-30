<?php

use App\Models\BankAccount;
use App\Models\User;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trnsaction_id')->nullable()->unique();
            $table->foreignIdFor(BankAccount::class)->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->enum('type', ['deposit', 'withdrawl', 'transfer']);
            $table->enum('transfer_type', ['send', 'recieved'])->nullable();
            $table->decimal('amount')->default(0);
            $table->decimal('opening_balance')->default(0);
            $table->decimal('closing_balance')->default(0);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
