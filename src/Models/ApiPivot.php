<?php

namespace Mapi\Easyapi\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Mapi\Easyapi\Traits\IsApiModel;

class ApiPivot extends Pivot
{
    use IsApiModel;
}