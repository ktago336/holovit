<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Amenitie extends Model
{
    use Sortable;
    //
    public $sortable = ['id','amenitie_name', 'status'];

   public function BusinessType()
	{
		return $this->belongsTo('App\Models\BusinessType', 'business_type');
	}

    
}
