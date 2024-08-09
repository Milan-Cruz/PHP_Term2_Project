<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage; // Add this line
use Intervention\Image\Facades\Image; // Add this line

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
