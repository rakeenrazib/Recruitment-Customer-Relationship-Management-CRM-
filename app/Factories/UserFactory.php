<?php

namespace App\Factories;

use App\Models\User;
use InvalidArgumentException;

class UserFactory
{
    /**
     * Create a new user based on the specified type.
     *
     * @param string $type
     * @param array $data
     * @return User
     * @throws InvalidArgumentException
     */
    public static function create(string $type, array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        if ($type === 'candidate') {
            $userData['role'] = 'candidate';
            return User::create($userData);
        } elseif ($type === 'recruiter') {
            $userData['role'] = 'recruiter';
            return User::create($userData);
        }

        throw new InvalidArgumentException("Invalid user type: {$type}");
    }
}
