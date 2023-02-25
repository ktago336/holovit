<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Location extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'location_name', 'slug','status','created_at'];
    
}
