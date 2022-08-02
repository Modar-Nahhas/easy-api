<?php

namespace Mapi\Easyapi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Mapi\Easyapi\Traits\IsApiModel;

class ApiPivot extends Pivot
{
    use HasFactory, IsApiModel;
}