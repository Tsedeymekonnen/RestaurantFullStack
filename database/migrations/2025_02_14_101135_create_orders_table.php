<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
      Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('menu_id');
        $table->unsignedBigInteger('user_id');
        $table->integer('quantity');
        $table->decimal('total_price', 8, 2); // NOT NULL, no default value
        $table->timestamps();
    
        $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      });
    }

    public function down() {
        Schema::dropIfExists('orders');
    }
};
