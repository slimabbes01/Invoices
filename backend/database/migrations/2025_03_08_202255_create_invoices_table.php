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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('seller_name');
            $table->string('buyer_name');
            $table->date('issue_date');
            $table->text('product_or_service_list');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('vat', 5, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_terms');
            $table->string('pdf_upload')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
