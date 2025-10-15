<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
	protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'status',
        'google_id',
        'facebook_id',
        'like_document',
        'address',
        'phone',
        'debt',
        'olddebt',
        'json_params'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['is_super_user'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'json_params' => 'object',
        'json_profiles' => 'object',
        'like_document' => 'array',
    ];

    /**
     * Add a mutator to ensure hashed passwords
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function addFavorites($id) {
        $likes = $this->like_document ?: [];
        $likes[] = $id;

        $this->update(['like_document' => $likes]);
    }

    public function removeFavorites($id) {
        $likes = $this->like_document ?: [];
        $likes = array_diff($likes, [$id]);

        $this->update(['like_document' => $likes]);
    }
}
