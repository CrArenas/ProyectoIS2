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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->float('amount', 5, 2);
            $table->float('V1', 20, 4);
            $table->float('V2', 20, 4);
            $table->float('V3', 20, 4);
            $table->float('V4', 20, 4);
            $table->float('V5', 20, 4);
            $table->float('V6', 20, 4);
            $table->float('V7', 20, 4);
            $table->float('V8', 20, 4);
            $table->float('V9', 20, 4);
            $table->float('V10', 20, 4);
            $table->float('V11', 20, 4);
            $table->float('V12', 20, 4);
            $table->float('V13', 20, 4);
            $table->float('V14', 20, 4);
            $table->float('V15', 20, 4);
            $table->float('V16', 20, 4);
            $table->float('V17', 20, 4);
            $table->float('V18', 20, 4);
            $table->float('V19', 20, 4);
            $table->float('V20', 20, 4);
            $table->float('V21', 20, 4);
            $table->float('V22', 20, 4);
            $table->float('V23', 20, 4);
            $table->float('V24', 20, 4);
            $table->float('V25', 20, 4);
            $table->float('V26', 20, 4);
            $table->float('V27', 20, 4);
            $table->float('V28', 20, 4);
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
