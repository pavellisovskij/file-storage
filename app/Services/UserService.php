<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Updates user's storage size, role and status
     *
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function update(User $user, array $data): bool
    {
        $data['storage_size'] = $data['storage_size'] * 1024 * 1024;

        return $user->update($data);
    }
}
