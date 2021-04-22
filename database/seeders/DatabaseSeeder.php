<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //TODO remove after testing
        if (config("app.env") == "local") {
            $user = new User();
            $user->email = 'jonas@jonas';
            $user->name = 'Jonas';
            $user->password = Hash::make('asdfasdf');
            $user->save();
        }
    }
}
