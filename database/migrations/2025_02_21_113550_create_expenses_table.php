<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Foreign key to categories table
            $table->decimal('amount', 10, 2);  // Amount spent
            $table->text('description')->nullable();  // Optional description of the expense
            $table->timestamp('expense_date');  // The date the expense occurred
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};
