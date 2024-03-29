<?php

namespace App;

use App\Post;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    function posts()
    {
        return $this->hasMany(Post::class);
    }

    function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    function scopePermitido($query)
    {
        if(Auth()->user()->can('view', $this))
        {
            return $query;
        }

        $posts = $query->where('id',Auth()->user()->id);
    }
}
