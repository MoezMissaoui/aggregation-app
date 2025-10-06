<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ApiResponser;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    use ApiResponser;

}