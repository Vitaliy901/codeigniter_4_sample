<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PageController extends BaseController
{
    public function documentation()
    {
        return view('swagger/view', ['domain' => getenv('app_baseURL')]);
    }
}
