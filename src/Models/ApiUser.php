<?php

namespace Mapi\Easyapi\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Mapi\Easyapi\Traits\IsApiModel;

class ApiUser extends Authenticatable
{
    use IsApiModel;
}
