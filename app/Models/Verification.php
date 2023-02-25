<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Verification extends Model
{
    use Sortable;
    //
    public $sortable = ['id','merchant_number', 'otp'];

   public function BusinessType()
	{
		return $this->belongsTo('App\Models\BusinessType', 'business_type');
	}

    
}
