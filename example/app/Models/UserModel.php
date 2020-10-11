<?php
/**
 * 테스트 모델
 *
 * @author inlee <einable@gmail.com>
 */
namespace App\Models;

use Foundation\Base\Model as BaseModel;

class UserModel extends BaseModel
{
    /**
     * 생성자
     */
    public function __construct()
    {
        parent::__construct('users');
    }
}
