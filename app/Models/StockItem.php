<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;

    // Add this relation to link StockItem to StockSubCategory
    public function stock_subcategory()
    {
        return $this->belongsTo(StockSubCategory::class, 'stock_subcategory_id');
    }



      //boot
    protected static function boot()
    {
        parent::boot();
        //throw an error if stock category is not set
        static::creating(function ($model) {
            $model = self::prepare($model);
            return $model;
           
        });
        static::updating(function ($model) {
            $model = self::prepare($model);
            return $model;
           
        });
    }
   static public function prepare($model)
{
    // Find subcategory automatically based on the category and company
    $sub_category = StockSubCategory::where('company_id', $model->company_id)
        ->where('stock_category_id', $model->stock_category_id)
        ->first();

  if ($sub_category == null) {
    $sub_category = StockSubCategory::create([
        'company_id' => $model->company_id,
        'stock_category_id' => $model->stock_category_id,
        'name' => 'Default Subcategory',
    ]);
}


    $model->stock_subcategory_id = $sub_category->id;

    $user = User::find($model->created_by);
    if (!$user) {
        throw new \Exception('Invalid user');
    }

    $financialPeriod = Utils::getActiveFinancialPeriod($user->company_id);
    if ($financialPeriod == null) {
        throw new \Exception('No active financial period found for this company');
    }

    $model->financial_period_id = $financialPeriod->id;

    static::updating(function ($model) {
        $stock_category = StockCategory::find($model->stock_category_id);
        $stock_category->update_self();

        $stock_subcategory = StockSubCategory::find($model->stock_subcategory_id);
        $stock_subcategory->update_self();
    });
    

    static::deleted(function ($model) {
        $stock_category = StockCategory::find($model->stock_category_id);
        $stock_category->update_self();

        $stock_subcategory = StockSubCategory::find($model->stock_subcategory_id);
        $stock_subcategory->update_self();
    });

    return $model;
}



    //getter for gallery
    public function getGalleryAttribute($value)
    {
        if ($value != null) {
            $gallery = json_decode($value, true);
            if (is_array($gallery)) {
                return $gallery;
            }
        }
        return [];  
}
//setter for gallery
public function setGalleryAttribute($value)
{
    $this->attributes['gallery'] = json_encode($value, true);

}}

