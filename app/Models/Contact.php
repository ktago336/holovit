<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Contact extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'first_name', 'last_name', 'phone', 'email_address', 'message','slug','created_at', 'updated_at'];
    
    
}
