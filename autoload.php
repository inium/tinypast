<?php
/**
 * 클래스 파일을 자동으로 Load 하기 위해 정의
 * composer의 vendor/autoload.php 와 같이 사용하기 위해 구현
 */
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require_once $class . '.php';
});
