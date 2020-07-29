<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedUsersData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            [
                'name' => 'yong',
                'email' => 'freeyongx@163.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'avatar' => 'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png',
            ],
            [
                'name' => 'lianzi',
                'email' => 'freeyongxx@163.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'avatar' => 'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png',
            ],
        ];

        \DB::table('users')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('users')->truncate();
    }
}
