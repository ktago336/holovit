<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Category extends Model
{
    use Sortable;
    //
    public $sortable = ['id','parent_id', 'category_name', 'slug','status','created_at'];
    
}
