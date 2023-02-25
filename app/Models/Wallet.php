<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Wallet extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'created_at'];
    
    //public function Merchant(){
    //    return $this->belongsTo('App\Models\Merchant');
    //} 
	public function User(){
        return $this->belongsTo('App\Models\User');
    }
	public function Merchant(){
        return $this->belongsTo('App\Models\Merchant');
    }
	public function Order(){
        return $this->belongsTo('App\Models\Order');
    }
	public function Withdrawal(){
        return $this->belongsTo('App\Models\Withdrawal');
    }
}
