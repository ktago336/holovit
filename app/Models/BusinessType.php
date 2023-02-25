<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class BusinessType extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'name', 'slug','status','created_at'];
    
}
