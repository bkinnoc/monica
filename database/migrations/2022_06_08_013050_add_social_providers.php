<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use MadWeb\SocialAuth\Models\SocialProvider;
use Illuminate\Database\Migrations\Migration;

class AddSocialProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        SocialProvider::create(['slug' => 'google', 'label' => 'Google']);
        SocialProvider::create(['slug' => 'facebook', 'label' => 'Facebook']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        SocialProvider::whereIn('slug', ['google', 'facebook'])->delete();
    }
}