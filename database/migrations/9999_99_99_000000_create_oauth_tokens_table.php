<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');

            //user relation
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            //provider information
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable()->index();
            $table->string('token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->integer('expires_in')->nullable();

            //unique constraint
//          $table->unique(['provider_user_id', 'provider',]);

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
        Schema::dropIfExists('oauth_tokens');
    }
}
