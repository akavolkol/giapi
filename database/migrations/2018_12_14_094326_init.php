<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Init extends Migration
{
    public function up()
    {
        Schema::create(
            'User',
            function (Blueprint $table) {
                $table->increments('id');
                $table
                    ->string('email')
                    ->unique();
                $table
                    ->string('password', 100);
                $table
                    ->string('avatar', 2000)
                    ->nullable();
            }
        );
    }
}
