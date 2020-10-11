<?php
/**
 * 사용자 컨트롤러
 *
 * @author inlee <einable@gmail.com>
 */
namespace App\Controllers;

use Foundation\Base\Controller as BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    private $model = null;

    /**
     * 생성자
     */
    public function __construct()
    {
        $this->model = new UserModel();
    }

    /**
     * 사용자 목록 페이지
     *
     * @param array $request    $_REQUEST
     */
    public function index($request)
    {
        $users = $this->model->findAll();

        return $this->render('../resources/views/users/index.php', array(
            'users' => $users
        ));
    }

    /**
     * 사용자 정보 보기 페이지
     *
     * @param array $request    $_REQUEST
     * @param integer $userId   사용자 ID
     */
    public function show($request, $userId)
    {
        $user = $this->model->findById($userId);

        return $this->render('../resources/views/users/show.php', array(
            'user' => $user
        ));
    }

    /**
     * 사용자 정보 추가 페이지
     *
     * @param array $request    $_REQUEST
     */
    public function create($request)
    {
        return $this->render('../resources/views/users/create.php', array());
    }

    /**
     * 사용자 정보 추가
     *
     * @param array $request    $_REQUEST
     */
    public function store($request)
    {
        $req = $this->sanitizeRequest($request);

        $params = array(
            'name' => $req['user_name'],
            'email' => $req['user_email'],
            'phone' => $req['user_phone'],
            'memo' => $req['user_memo'],
            'created_at' => date('Y-m-d H:i:s')
        );

        $insertId = $this->model->insert($params);

        return $this->redirect("/users/{$insertId}");
    }

    /**
     * 사용자 정보 수정 페이지
     *
     * @param array $request    $_REQUEST
     * @param integer $userId   수정 대상 사용자 ID
     */
    public function edit($request, $userId)
    {
        $user = $this->model->findById($userId);

        return $this->render('../resources/views/users/edit.php', array(
            'user' => $user
        ));
    }

    /**
     * 사용자 정보 수정
     *
     * @param array $request    $_REQUEST
     * @param integer $userId   수정 대상 사용자 ID
     */
    public function update($request, $userId)
    {
        $req = $this->sanitizeRequest($request);

        $params = array(
            'name' => $req['user_name'],
            'email' => $req['user_email'],
            'phone' => $req['user_phone'],
            'memo' => $req['user_memo']
        );

        $rows = $this->model->update($params, $userId);

        return $this->redirect("/users/{$userId}");
    }

    /**
     * 사용자 삭제
     *
     * @param array $request    $_REQUEST
     * @param integer $userId   삭제 대상 사용자 ID
     */
    public function delete($request, $userId)
    {
        $rows = $this->model->deleteById($userId);

        return $this->redirect('/users');
    }
}
