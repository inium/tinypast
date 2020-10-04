<?php

namespace App\Controllers;

use Foundation\BaseController;

class ErrorController extends BaseController
{
    /**
     * 404 Not found
     */
    public function notFound($request)
    {
        return '404 Not Found';
    }

    /**
     * 405 Method not allowed
     */
    public function methodNotAllowed($request)
    {
        return '405 Method Not Allowed';
    }
}
