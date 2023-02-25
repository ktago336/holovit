<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Notification extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'from_name', 'user_id','message','url', 'status','created_at','updated_at','slug'];
    
}
