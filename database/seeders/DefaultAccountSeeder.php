<?php

namespace Database\Seeders;

use App\Domains\Account\Models\Account;
use Illuminate\Database\Seeder;

class DefaultAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Account::count() === 0) {
            Account::create(['name' => 'Bank', 'balance' => 0.00]);
            Account::create(['name' => 'Cash', 'balance' => 0.00]);
        }
    }
}
