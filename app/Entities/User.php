<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class User.
 *
 * @package namespace App\Entities;
 */
class User extends Authenticatable implements Transformable
{
    use TransformableTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'email',
        'url',
        'status',
        'url',
        'password',
        'allow_login',
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

    function getAvatarAttribute()
    {
        return gravatar($this->email);
    }

    /**
     * The metas of this user.
     *
     * @return HasMany
     */
    public function metas(): HasMany
    {
        return $this->hasMany(UserMeta::class, 'user_id');
    }

    /**
     * The posts belongs this user.
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function allPermissions()
    {
        return array();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function menus()
    {
        $menuGroup = $this->roles()->first()->name;
        $menus = Menu::with(['parent', 'group'])->where([
            ['parent_id', '=', 0],
        ])->withCount('children')->with('children')->orderBy('order')->get();
        dd($menus);
        return $menus;
    }

}
