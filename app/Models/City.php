<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class City extends Model {

    use Sortable;

    //
    public $sortable = ['id','state_id', 'name', 'status'];


}
