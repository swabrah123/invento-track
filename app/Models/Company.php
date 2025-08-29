<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'Owner_id',
        'name',
        'email',
        'website',
        'logo',
        'status',
        'address',
        'phone1',
        'phone2',
        'pobox',
        'colour',
        'slogan',
        'expiry_license',
    ];

    public function administrator()
    {
        return $this->belongsTo(User::class, 'Owner_id');
    }

    protected static function boot()
{
    parent::boot();

   

    static::created(function ($model) {
        $owner = User::find($model->Owner_id);
        if ($owner == null) {
            throw new \Exception('Owner not found');
        }
        $owner->company_id = $model->id;
        $owner->save();
        // Runs after insert
    });

    static::updated(function ($model) {
        $owner = User:: find($model->Owner_id);
        if ($owner == null) {
            throw new \Exception('Owner not found');
        } 
        $owner->company_id = $model->id;
        $owner->save();
    });

    

    
}

}
