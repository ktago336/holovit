<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Citie extends Model {

    use Sortable;

    //
    public $sortable = ['id','state_id', 'city_name', 'status'];


}
