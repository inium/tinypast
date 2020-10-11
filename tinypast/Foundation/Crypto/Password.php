<?php
/**
 * 비밀번호 해시코드 생성 클래스
 *
 * php의 비밀번호 해시코드 생성/검증 함수인 password_hash, password_verify는
 * 5.5 버전부터 제공하기 때문에 해당 함수가 없을 경우 아래 require 경로의 파일을
 * 사용
 *
 * @see https://opentutorials.org/course/697/3984
 */

namespace Foundation\Crypto;

require_once __DIR__ .
    '/../../vendor/ircmaxwell/password_compact/lib/password.php';

class Password
{
    /**
     * 비밀번호를 bcrypt hashcode로 생성한다.
     *
     * @param string $password  변환 대상 비밀번호
     * @return string           hashcode
     */
    public static function hash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * 사용자가 입력한 비밀번호와 hashcode가 일치하는지 검사한다.
     *
     * @param string $password  검증 대상 비밀번호
     * @param string $hash      검증 대상 hashcode
     * @return boolean
     */
    public static function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
