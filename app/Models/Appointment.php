<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Appointment extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'service_ids', 'user_id', 'staff_id', 'booking_date_time', 'status', 'slug', 'description', 'total_price','created_at', 'updated_at'];


    public function Service()
	{
		return $this->belongsTo('App\Models\Service', 'service_ids');
	}
	public function User()
	{
		return $this->belongsTo('App\Models\User', 'user_id');
	}
	public function Admin()
	{
		return $this->belongsTo('App\Models\Admin', 'staff_id');
	}
}
