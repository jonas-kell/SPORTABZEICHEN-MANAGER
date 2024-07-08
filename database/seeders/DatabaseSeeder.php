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
        if (app()->isLocal()) {
            $root = new User();

            $root->name = "TEST ROOT USER";
            $root->email = "test@test";
            $root->password = bcrypt("asdfasdf");

            $root->save();
        }
    }
}
