<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Expense extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();

        Schema::create('expense_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('expense_category_id')->nullable()->index()->constrained('expense_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedDecimal('amount', 20,2)->deafult(0);
            $table->text('note')->nullable();
            $table->enum('payment_method', ['cash', 'bkash', 'due', 'others'])->default('cash');
            $table->timestamps();
            $table->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::drop('expense_categories');
        Schema::drop('expenses');
        
    }
}
