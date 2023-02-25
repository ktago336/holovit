<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Banner extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'title', 'slug','status','created_at'];
    
}
