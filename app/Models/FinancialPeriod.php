<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialPeriod extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        //throw an  error cant have two active financial periods but allow cratining one which is not active
        //if there is already an active financial period for the company    

        static::creating(function ($model) {
            if ($model->status == 'active') {
                $activePeriod = FinancialPeriod::where('company_id', $model->company_id)
                    ->where('status', 'active')
                    ->first();

                if ($activePeriod) {
                    throw new \Exception('There is already an active financial period for this company.');
                }
            }
        });
        static::updating(function ($model) {
            if ($model->status == 'active') {
                $activePeriod = FinancialPeriod::where('company_id', $model->company_id)
                    ->where('status', 'active')
                    ->first();

                if ($activePeriod && $activePeriod->id != $model->id) {
                    throw new \Exception('There is already an active financial period for this company.');
                }
            }
        });
    }
    
}
