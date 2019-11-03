<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitialStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['u', 'o', 'a']);
            $table->boolean('active')->default(true);
            $table->string('photo')->nullable();
            $table->string('nif', '9')->nullable();
        });


        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['e', 'i']);
            $table->string('name');
        });        

        Schema::create('wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->primary();
            $table->foreign('id')->references('id')->on('users');
            $table->string('email')->unique();
            $table->decimal('balance', 9, 2)->default(0);
            $table->timestamps();            
        }); 

        Schema::create('movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wallet_id');
            $table->foreign('wallet_id')->references('id')->on('wallets');
            $table->enum('type', ['e', 'i']);
            $table->boolean('transfer');
            $table->unsignedBigInteger('transfer_movement_id')->nullable();
            $table->unsignedBigInteger('transfer_wallet_id')->nullable();
            $table->foreign('transfer_wallet_id')->references('id')->on('wallets');
            $table->enum('type_payment', ['c', 'bt', 'mb'])->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('iban', 25)->nullable();
            $table->string('mb_entity_code', 5)->nullable();
            $table->string('mb_payment_reference', 9)->nullable();
            $table->text('description')->nullable();
            $table->text('source_description')->nullable();
            $table->dateTime('date');
            $table->decimal('start_balance', 9, 2);
            $table->decimal('end_balance', 9, 2);
            $table->decimal('value', 9, 2);
        });       

        Schema::table('movements', function (Blueprint $table) {
            $table->foreign('transfer_movement_id')->references('id')->on('movements');
        }); 

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movements');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('categories');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['type', 'active', 'photo', 'nif']);
        });
    }
}
