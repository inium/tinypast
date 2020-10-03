<?php
/**
 * 메인 페이지
 *
 * @author inlee <einable@gmail.com>
 */

namespace App\Controllers;

use Foundation\BaseController;

class HomeController extends BaseController
{
    /**
     * 생성자
     */
    public function __construct()
    {
    }

    /**
     * 메인 페이지
     *
     * @param array $request    $_REQUEST
     */
    public function index($request)
    {
        return $this->render('../resources/views/home.php');
    }

    /**
     * phpinfo() 출력
     *
     * @param array $request    $_REQUEST
     */
    public function info($request)
    {
        return phpinfo();
    }
}
