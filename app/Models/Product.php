<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model {

    use Sortable;

    //
    public $sortable = ['id', 'category_id', 'subcategory_id','subsubcategory_id','merchant_id', 'brand_id', 'location_id', 'status', 'slug', 'description', 'price','created_at', 'updated_at'];

//    public function Service() {
//        return $this->belongsTo('App\Models\Service', 'service_ids');
//    }
//
//    public function User() {
//        return $this->belongsTo('App\Models\User', 'user_id');
//    }
//
    public function Category() {
        return $this->belongsTo('App\Models\Category','category_id');
    }
    
      public function Brand() {
        return $this->belongsTo('App\Models\Brand','brand_id');
    }
    
     public function Sale() {
        return $this->belongsTo('App\Models\Sale','category_id');
    }
    

}
