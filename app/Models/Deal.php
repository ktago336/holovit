<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Deal extends Model {

    use Sortable;

    //
    public $sortable = ['id', 'product_id','merchant_id','status', 'slug','deal_name','discount','final_price', 'description', 'price','created_at', 'updated_at', 'expire_date'];

//    public function Service() {
//        return $this->belongsTo('App\Models\Service', 'service_ids');
//    }
//
//    public function User() {
//        return $this->belongsTo('App\Models\User', 'user_id');
//    }
//
    public function Product() {
        return $this->belongsTo('App\Models\Product','product_id');
    }
	public function Merchant() {
        return $this->belongsTo('App\Models\Merchant','merchant_id');
    }
    
    

}
