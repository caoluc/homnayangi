<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface
{

    use UserTrait, RemindableTrait;

    const STATUS_BAN = 0;
    const STATUS_NORMAL = 1;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $fillable = ['account_id', 'chatwork_id', 'name', 'organization_id', 'organization_name', 'role', 'avatar_image_url'];

    public function votes()
    {
        return $this->hasMany('Vote');
    }

    public function isBanned()
    {
        return $this->status == self::STATUS_BAN;
    }
}
