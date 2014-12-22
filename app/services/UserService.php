<?php

class UserService
{
    public static function createOrUpdate($data)
    {
        $accountId = $data['account_id'];
        $user = User::where('account_id', $accountId)->first();
        if ($user) {
            $user->update($data);
        } else {
            $user = User::create($data);
        }

        return $user;
    }
} 