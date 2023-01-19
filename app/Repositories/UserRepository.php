<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * create a user instance
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function create($data)
    {
        return $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    }

    /**
     * create a user with customer role
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function createCustomer($data)
    {
        return $this->create(array_merge($data, [
            'role' => Role::CUSTOMER
        ]));
    }

    /**
     * create a user with admin role
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function createAdmin($data)
    {
        return $this->create(array_merge($data, [
            'role' => Role::ADMIN
        ]));
    }
}
