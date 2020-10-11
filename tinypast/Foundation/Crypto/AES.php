<?php
/**
 * PHP AES 상속 클래스
 *
 * AES 순수 구현체를 상속받아 사용
 *
 * TODO: openssl, mcrypt 플러그인 등이 없을 경우 순수 구현체를 사용하도록 수정,
 * openssl, mcrypt 플러그인 존재할 시 해당 함수를 사용할 수 있도록 factory
 * 형태로 구현
 */

namespace Foundation\Crypto;

require_once __DIR__ . '/../../vendor/phillipsdata/phpaes/src/Aes.php';

class Aes extends \PhpAes\Aes
{
}
