<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('bill_id');
            $table->integer('item_id')->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->double('quantity', 7, 2);
            $table->double('price', 15, 4);
            $table->double('total', 15, 4);
            $table->float('tax', 15, 4)->default('0.0000');
            $table->integer('tax_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_items');
    }
}
