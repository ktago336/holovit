<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Order extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'created_at'];
    
    public function Merchant(){
        return $this->belongsTo('App\Models\Merchant');
    } 
	public function User(){
        return $this->belongsTo('App\Models\User');
    }
	public function Payment()
	{
		return $this->hasOne('App\Models\Payment', 'order_id', 'id')->where(['status' => 1]);
	}
	
}
