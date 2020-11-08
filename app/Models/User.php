<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param string $size
     * @return string
     * @version  2020-11-1 12:13
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = Str::random(10);
            $user->activated = false;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @version  2020-11-5 10:44
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function statuses()
    {
        return $this->hasMany(Status::class, 'user_id', 'id');
    }

    public function feed()
    {
        return $this->statuses()->orderBy('created_at', 'desc');
    }

    public function followers()
    {
        return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }

    /**
     * 关注用户
     *
     * @param $user_ids
     * @version  2020-11-8 11:04
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function follow($user_ids)
    {
        if (! is_array()) {
            $user_ids = compact(['user_ids']);
        }

        $this->followings()->sync($user_ids, false);
    }

    /**
     * 取消关注
     *
     * @param $user_ids
     * @version  2020-11-8 11:05
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function unfollow($user_ids)
    {
        if (! is_array()) {
            $user_ids = compact(['user_ids']);
        }
        $this->followings()->detach($user_ids);
    }

    /**
     * 判断关注的用户钟是否包含此id
     *
     * @param $userId
     * @return mixed
     * @version  2020-11-8 11:08
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function isFollowing($userId)
    {
        return $this->followings()->contains($userId);
    }
}
