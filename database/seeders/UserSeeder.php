<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hireflixclone.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create reviewer user
        User::create([
            'name' => 'Reviewer User',
            'email' => 'reviewer@hireflixclone.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'email_verified_at' => now(),
        ]);

        // Create additional reviewer for testing
        User::create([
            'name' => 'John Reviewer',
            'email' => 'john.reviewer@hireflixclone.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'email_verified_at' => now(),
        ]);

        // Create another admin for testing
        User::create([
            'name' => 'HR Manager',
            'email' => 'hr@hireflixclone.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Default users created successfully!');
        $this->command->info('');
        $this->command->info('🔑 Admin Credentials:');
        $this->command->info('   Email: admin@hireflixclone.com');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('   Email: hr@hireflixclone.com');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('👥 Reviewer Credentials:');
        $this->command->info('   Email: reviewer@hireflixclone.com');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('   Email: john.reviewer@hireflixclone.com');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('💡 Note: Candidates don\'t need accounts - they access interviews via email links');
    }
}
