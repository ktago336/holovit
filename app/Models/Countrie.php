<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Countrie extends Model {

    use Sortable;

    //
    public $sortable = ['id', 'country_name', 'status'];


}
