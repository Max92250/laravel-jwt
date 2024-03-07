<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Retrieve the user ID passed as an option
        $userId = $this->option('user_id');

        // Retrieve the user based on the provided ID
        $user = User::find($userId);

        if ($user) {
            // Your logic to update user roles...
            $this->updateUserRoles($user, [/* Array of role IDs */]);

            $this->command->info('User roles updated successfully in seeder.');
        } else {
            $this->command->error('User not found.');
        }
    }

    private function updateUserRoles(User $user, array $roleIds)
    {
        $user->roles()->sync($roleIds);
    }
}
