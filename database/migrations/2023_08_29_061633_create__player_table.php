<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Player', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('account', 50);
            $table->string('displayName', 50);
            $table->string('password', 255);
            $table->string('email', 255);
            $table->enum('status', ['active', 'blocked', 'inactive']);
            $table->decimal('balance', 10, 2);
            $table->string('lastLoggedInIp', 150);
            $table->timestamp('lastLoggedInAt');
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Player');
    }
};
