<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
          [
              'name' => 'Author unknowns',
              'email' =>'uncknowns@go.com',
              'password' => bcrypt(Str::random(16))
          ],
          [
              'name' => 'Andrey',
              'email' =>'halat@tut.by',
              'password' => bcrypt('1234567')
          ]
        ];

        DB::table('users')->insert($data);
    }
}
