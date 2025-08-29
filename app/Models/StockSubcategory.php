<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSubcategory extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'company_id',
        'stock_category_id',
        'name',
        'description',
        'status',
        'Image',
        'buying_price',
        'selling_price',
        'expected_profit',
        'earned_profit',
        'measuring_unit',
        'current_quantity',
        'reorder_level'
    ];

    // Append custom attribute
    protected $appends = ['name_text'];

    /**
     * Update this stock subcategory's summary fields based on related stock items.
     */
    public function updateSelf()
    {
        $active_financial_period = Utils::getActiveFinancialPeriod($this->company_id);
        if ($active_financial_period == null) {
            return;
        }

        $total_buying_price = 0;
        $total_selling_price = 0;
        $current_quantity = 0;

        // Get related stock items for this subcategory in current financial period
        $stock_items = StockItem::where('stock_subcategory_id', $this->id)
            ->where('company_id', $this->company_id)
            ->where('financial_period_id', $active_financial_period->id)
            ->get();

        foreach ($stock_items as $item) {
            $total_buying_price += ($item->buying_price * $item->current_quantity);
            $total_selling_price += ($item->selling_price * $item->current_quantity);
            $current_quantity += $item->current_quantity;
        }

        // Calculate total sales for earned profit
        $total_sales =StockRecord::where('stock_subcategory_id', $this->id)
            ->where('company_id', $this->company_id)
            ->where('financial_period_id', $active_financial_period->id)
            ->where('type', 'out')
            ->sum('amount');
            $this->earned_profit = $total_sales;  

        // Update model fields
        $this->buying_price = $total_buying_price;
        $this->selling_price = $total_selling_price;
        $this->current_quantity = $current_quantity;
        $this->expected_profit = $total_selling_price - $total_buying_price;
        $this->earned_profit = $total_sales - $total_buying_price;

        // Set stock status
        if ($current_quantity > $this->reorder_level) {
            $this->status = 'in_stock';
        } else {
            $this->status = 'out_of_stock';
        }

        $this->save();
    }
    //earned_profit is calculated as total sales - total buying price
    

    /**
     * Relationship: StockSubcategory belongs to StockCategory.
     */
    public function stockCategory()
    {
        return $this->belongsTo(StockCategory::class, 'stock_category_id');
    }

    /**
     * Relationship: StockSubcategory belongs to Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Accessor for full name text combining category and subcategory names.
     */
    public function getNameTextAttribute()
    {
        $name_text = $this->name;
        if ($this->stockCategory) {
            $name_text = $this->stockCategory->name . ' - ' . $this->name;
        }
        return $name_text;
    }
}
