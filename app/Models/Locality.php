<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Locality extends Model {

    use Sortable;

    //
    public $sortable = ['id','city_id', 'locality_name', 'status'];


}
