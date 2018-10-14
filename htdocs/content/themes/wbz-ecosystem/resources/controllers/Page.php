<?php

namespace Theme\Controllers;

use Themosis\Route\BaseController;

class Page extends BaseController
{
    public function notFound()
    {
        return view('404');
    }
}