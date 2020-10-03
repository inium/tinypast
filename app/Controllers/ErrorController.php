<?php

namespace App\Controllers;

use Foundation\BaseController;

class ErrorController extends BaseController
{
    /**
     * 404 Not found
     */
    public function notFound()
    {
        return '404 Not Found';
    }

    /**
     * 405 Method not allowed
     */
    public function methodNotAllowed()
    {
        return '405 Method Not Allowed';
    }
}
