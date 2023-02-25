<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Coupon extends Model {

    use Sortable;

    //
    public $sortable = ['id', 'user_id','product_id', 'coupon_code','description','discount_offer', 'expiry_date', 'slug', 'status', 'created', 'modified'];

//    public function Service() {
//        return $this->belongsTo('App\Models\Service', 'service_ids');
//    }
//
//    public function User() {
//        return $this->belongsTo('App\Models\User', 'user_id');
//    }
//
    public function Product() {
        return $this->belongsTo('App\Models\Product');
    }
    
  
    

}
