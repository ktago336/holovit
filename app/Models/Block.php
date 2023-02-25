<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Block extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'slug', 'staff_id', 'full_day','start_date', 'start_time', 'end_time', 'status', 'created'];


 //    public function Service()
	// {
	// 	return $this->belongsTo('App\Models\Service', 'service_ids');
	// }
	// public function User()
	// {
	// 	return $this->belongsTo('App\Models\User', 'user_id');
	// }
	// public function Admin()
	// {
	// 	return $this->belongsTo('App\Models\Admin', 'staff_id');
	// }
}
