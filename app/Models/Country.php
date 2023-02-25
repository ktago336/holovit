<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Country extends Model {

    use Sortable;

    //
    public $sortable = ['id', 'name'];


}
