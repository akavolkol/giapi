<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Thumbnails extends Migration
{
    public function up()
    {
        Schema::table(
            'User',
            function (Blueprint $table) {
                $table
                    ->string('avatarThumbnail', 2000)
                    ->nullable();
            }
        );
    }
}
