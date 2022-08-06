<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbandonedCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abandoned_cart', function (Blueprint $table) {
            $table->id();
            $table->text('email');
            $table->text('first_name')->nullable();
            $table->text('last_name')->nullable();
            $table->text('mailbox_key')->nullable();
            $table->timestamp('last_emailed_at')->nullable();
            $table->timestamp('signed_up_at')->nullable();
            $table->integer('emailed_times')->default(0);
            $table->integer('user_id')->length(10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abandoned_cart');
    }
}