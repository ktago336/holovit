<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Brand extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'brand_name', 'slug','status','created_at'];
    
}
