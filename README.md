# tinypast

Legacy PHP (5.3.x 이상) 에서 사용하기 위해 Apache2 환경에서 동작하는 만든 매우 간단한 Framework 입니다.

A very simple PHP framework for legacy environments on Apache2.


## 개요

Legacy PHP (5.3.x) 에서 구조적인 개발을 하기 위해 제작한 Apache2 환경에서 동작하는 매우 간단한 Framework 입니다.

대부분의 PHP Framework들이 Modern PHP를 위해 PHP 표준 권고안(PSR)을 따라 제작되고 있으며 [PHP에서 지원하는](https://www.php.net/supported-versions.php) 7.2 버전부터 공식적으로 지원하고 있습니다(글 작성 시점 기준). 그러나 현재 PHP로 제작되어 운영되는 사이트들 일부는 Framework 없이 개발되었으며 관리가 되지 않아 유지보수 및 추가 개발에 어려움이 있습니다. 또한 이 시점에서 사용된 PHP는 5.x 버전대가 대부분이며 이는 현재 Legacy가 되어 보안이슈, 최신 언어 기능 미지원 등으로 더 이상 사용이 권고되지 않습니다.

가장 쉬운 해결 방법은 PHP 7 이상으로 `Laravel`과 같은 Framework를 도입해 새로 개발하는 것이나 이는 많은 시간과 비용을 필요로 합니다. 이와 별도로 PHP 버전을 업그레이드 하는 것은 소스코드에 사용된 현재 Deprecated된 Feature 들로 인해 Side effect가 발생할 가능성이 매우 높기 때문에 매우 신중히 결정 후 진행해야 합니다.

그렇다면 가장 현실적인 방법으로는 기존 PHP 버전을 유지한 채 유지보수 과정에서 MVC 도입 혹은 유사하게 구현해 나가야 하며 특정 사이트 혹은 페이지들, 디렉터리에 Framework를 도입해 개발을 하며 조금씩 바꾸어 나가는 방법일 것입니다.

본 프로젝트는 이러한 방법에 사용하기 위해 구현된 매우 간단한 Framework 입니다. Legacy PHP인 5.3.x 이상에서 동작할 수 있도록 Route Pattern을 도입하였습니다. `composer` 없는 환경을 고려하여 `composer` 없이 본 프로젝트를 다운로드 받아 사용할 수 있도록 구현하였습니다.

본 프로젝트는 Apache2 환경에서 구현 및 테스트가 이루어졌으며 자세한 내용은 아래와 같습니다.

## Getting Started



### Install

본 프레임워크는 `composer` 를 사용하지 않는 Legacy PHP를 고려하였으며 `Codeigniter` 과거 버전과 같이 직접 다운로드하여 사용하도록 하였습니다.



### Model

### View



sanitize on/off





### Controller





### Route

#### Route Rules



## Security

XSS

SQL Injection

CSRF

세션에 저장되는 값과 비교

추후 구현 예정

## Example





## Future Works

XSS Clean

Password

php 5.3 password_hash 미지원(bcrypt) 하므로 이 모듈을 사용 https://github.com/ircmaxell/password_compat

UUID

https://www.php.net/manual/en/function.uniqid.php#94959



CSRF

https://stackoverflow.com/questions/6287903/how-to-properly-add-cross-site-request-forgery-csrf-token-using-php

https://github.com/indigophp/hash-compat

https://github.com/indigophp/hash-compat/blob/master/src/hash_equals.php



Session

별도로 구현

php 5.3에서 SessionHandlerInterface 미지원

https://gist.github.com/asika32764/5fdb8137ca4af30254b9



## References

본 프레임워크는 simplepixel 의 글을 기반으로 제작되었습니다.

## License

MIT