<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Service extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'name', 'price', 'description', 'slug', 'status', 'created_at', 'updated_at'];

    public function Appointment()
    {
       return $this->hasMany('App\Models\Appointment');
    }
    public function Admin()
    {
       return $this->hasMany('App\Models\Admin');
    }
}
