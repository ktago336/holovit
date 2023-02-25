<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class State extends Model {

    use Sortable;

    //
    public $sortable = ['id', 'country_id','name	', 'status'];


}
