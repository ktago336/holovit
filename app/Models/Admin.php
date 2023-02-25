<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Admin extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'first_name', 'email','created_at'];

    public function Appointment()
    {
       return $this->hasMany('App\Models\Appointment');
    }
    public function Service()
	{
		return $this->belongsTo('App\Models\Service', 'service_ids');
	}
    
}


