<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Administrator
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admin_users';
    //company
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // Add these if you want to mass assign them
        'first_name',
        'last_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

      static::creating(function ($model) {
    // name generation
    if ($model->first_name != null && strlen($model->first_name) > 0) {
        $model->name = $model->first_name;
    }
    if ($model->last_name != null && strlen($model->last_name) > 0) {
        $model->name = isset($model->name) && strlen($model->name) > 0
            ? $model->name . ' ' . $model->last_name
            : $model->last_name;
    }

    $name = trim($model->name ?? '');
    if ($name != null && strlen($name) > 0) {
        $model->name = $name;
    } else {
        $model->name = 'No Name';
    }

    // set default password if not provided
    if (empty($model->password)) {
        $model->password = bcrypt('123');
    }
});

        static::updating(function ($model) {
            // name to be generated from first and last name but first check if not null and not empty then update the name
            if ($model->first_name != null && strlen($model->first_name) > 0) {
                $model->name = $model->first_name;
            }
            if ($model->last_name != null && strlen($model->last_name) > 0) {
                // append last name to existing name if it exists
                $model->name = isset($model->name) && strlen($model->name) > 0
                    ? $model->name . ' ' . $model->last_name
                    : $model->last_name;
            }

            $name = trim($model->name ?? '');
            if ($name != null && strlen($name) > 0) {
                $model->name = $name;
            } else {
                $model->name = 'No Name';
            }
        });
    }
}
