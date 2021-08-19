<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin@ipt.pt';
        $admin->password = bcrypt('admin');
        $admin->email_verified_at = date("Y-m-d H:i:s");
        $admin->admin = true;
        $admin->last_ip_address = '127.0.0.1';
        $admin->save();

        $user = new User();
        $user->name = 'User';
        $user->email = 'user@ipt.pt';
        $user->password = bcrypt('user');
        $user->email_verified_at = date("Y-m-d H:i:s");
        $user->last_ip_address = '127.0.0.1';
        $user->save();
    }
}
