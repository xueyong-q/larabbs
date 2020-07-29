<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedOauthClientsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data[] = [
            'name' => 'larabbs-ios',
            'secret' => 'fdHrjM4KSeIsStxmx5cwAchq3SBWPr91pCxUM40H',
            'provider' => 'users',
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => '2020-07-29',
            'updated_at' => '2020-07-29',
        ];

        \DB::table('oauth_clients')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('oauth_clients')->truncate();
    }
}
