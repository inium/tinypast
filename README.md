# tinypast

Legacy PHP 에서(5.3.29 이상) 사용하기 위해 Apache2 환경에서 동작하는 만든 매우 간단한 Framework 입니다.

A very simple PHP framework for legacy environments on Apache2.


## 개요

Legacy PHP (5.3.29 이상) 에서 구조적인 개발을 하기 위해 제작한 매우 간단한 Framework 입니다.

대부분의 PHP Framework들이 Modern PHP를 위해 PHP 표준 권고안(PSR)을 따라 제작되고 있으며 [PHP에서 지원하는](https://www.php.net/supported-versions.php) 7.2 버전부터 공식적으로 지원하고 있습니다(글 작성 시점 기준). 그러나 현재 PHP로 제작되어 운영되는 사이트 일부는 Framework 없이 개발되었으며 관리가 되지 않아 유지보수 및 추가 개발에 어려움이 있습니다. 또한 이 시점에서 사용된 PHP는 5.x 버전대가 대부분이며 이는 현재 Legacy가 되어 보안이슈, 최신 언어 기능 미지원 등으로 더 이상 사용이 권고되지 않습니다.

가장 쉬운 해결 방법은 PHP 7 이상으로 `Laravel`과 같은 Framework를 도입해 새로 개발하는 것이나 이는 많은 시간과 비용을 필요로 합니다. 이와 별도로 PHP 버전을 업그레이드 하는 것은 소스코드에 사용된 현재 Deprecated된 Feature 들로 인해 Side effect가 발생할 가능성이 매우 높기 때문에 매우 신중히 결정 후 진행해야 합니다.

그렇다면 가장 현실적인 방법으로는 기존 PHP 버전을 유지한 채 유지보수 과정에서 MVC 도입 혹은 유사하게 구현해 나가야 하며 특정 사이트 혹은 페이지들, 디렉터리에 Framework를 도입해 개발을 하며 조금씩 바꾸어 나가는 방법일 것입니다.

본 프로젝트는 이러한 방법에 사용하기 위해 구현된 매우 간단한 Framework 입니다. Legacy PHP인 5.3.x 이상에서 동작할 수 있도록 Route Pattern을 도입하였습니다. `composer` 없는 환경을 고려하여 `composer` 없이 본 프로젝트를 다운로드 받아 사용할 수 있도록 구현하였습니다.

본 프로젝트는 Apache2 환경에서 구현 및 테스트가 이루어졌으며 자세한 내용은 아래와 같습니다.

## Getting Started

### Server Requirements

본 Framework는 아래의 서버 요구사항을 만족해야 합니다.

- php 5.3.29 or upper
- apache2 mod_rewrite on
- SimpleXML

### Install

본 Framework는 `composer` 를 사용하지 않는 PHP 환경을 고려하였습니다. 다운로드 한 후 Project root에 복사하여 사용합니다.

### 실행

APM 환경에서 Project root에 본 Framework를 저장하여 실행합니다. 또는 `docker` 가 설치되어 있을 경우 Project root 디렉터리에서 아래와 같이 `docker-compose` 명령어를 이용해 실행합니다.

```bash
docker-compose up
```

docker-compose 명령어는 docker 공식 `php:5.3-apache`를 이용한 후 mod_rewrite on을 하여 사용합니다. 자세한 내용은 Project root의 `docker-compose.yml` 과  `Dockerfile` 참조 바랍니다.

## Structures

본 Framework의 구조는 아래와 같습니다.

- root
  - app
    - Controllers : Controller 저장 디렉터리
    - Models: Model 저장 디렉터리 (Optional).
  - example: 사용자(Users) 정보 단순 CRUD 구현 Model, View, Controller 저장
  - tinypast
    - foundation: Framework Core Module
    - vendor: 외부 모듈(password_compact, AES 등)
    - Autoloader.php: namespace autoload (require) 실행
  - public: Framework 실행 파일(index.php), CSS, JavaScript, font, image 등 저장
  - resources
    - views: View 파일 저장
  - .env.example: .env 템플릿 파일
  - docker-compose.yml: Docker 실행환경 저장
  - Dockerfile: Docker 이미지
  - routes.xml: Route 저장 xml

## Features

본 Framework에서 구현한 기능은 아래와 같습니다. 사용 방법은 example의 Users 예시 참조 바랍니다.

### Namespace autoload

Namespace를 도입하였으며 Namespace 와 Class 이름으로 require 할 수 있습니다 ( `autoload.php` 참조) .

### RESTful

아래와 같이 RESTful method를 사용합니다.

- GET
- POST
- PUT
- PATCH
- DELETE

PUT, PATCH, DELETE method 사용은 아래와 같이 HTML form의 magic method를 명시하여 사용할 수 있습니다.

```html
<!-- PUT method: 사용자 정보 수정 -->
<form action="/user/1" method="POST">
	<input type="hidden" name="_method" value="PUT">
  ...
</form>

<!-- DELETE method: 사용자 정보 삭제 -->
<form action="/user/1" method="POST">
	<input type="hidden" name="_method" value="DELETE">
  ...
</form>
```

### MVC

Model View Controller를 이용해 구현하며 Route에 정의하여 사용합니다.

#### Controller

`Foundation\BaseController`를 상속하여 구현합니다. RESTful Method에 대응하는 멤버 함수들은 request 배열을 인자로 받으며 이후 route의 url parameter를 받습니다. `Foundation\BaseController`는 View 렌더링 & sanitize (공백, 탭 제거 / 주석제거), 배열 값의 XSS filter, redirect 가 구현되어 있습니다.

사용 예시는 아래와 같습니다.

```php
<?php
namespace App\Controllers;

use Foundation\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
	private $model = null;
  
  public function __construct() 
  {
		$this->model = new UserModel();  
  }
  
  // 사용자 목록
  public function index($request) 
  {
    $users = $this->model->findAll();
    return $this->render('../resources/views/users/index.php', array(
      'users' => $users
    ));
  }
  
  // 사용자 정보 수정
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
}
```

#### Model

데이터베이스의 값을 CRUD 하며 `Foundation\BaseModel`을 상속받아 구현합니다. `Foundation\BaseModel`에는 아래와 같은 기본적인 CRUD 가 구현되어 있습니다.

- findAll(): 데이터베이스에 저장된 모든 row를 가져온다.
- findById($id): $id에 해당하는 row를 가져온다.
- Insert($param): 데이터 삽입. 삽입할 값이 저장된 $param은 (필드명 => 값) 으로 구성된 배열이 입력되어야 함.
- Update($param, $id):  $id에 해당하는 row의 데이터 갱신. 갱신될 값이 저장된 $param은 (필드명 => 값) 으로 구성된 배열이 입력되어야 함.
- DeleteById($id): $id에 해당하는 row를 삭제한다.

```php
<?php
namespace App\Models;

use Foundation\BaseModel;

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
```

#### View

Controller에서 호출한 `render()` 함수의 첫 번째 파라미터의 View 파일(.php)에 2번째 인자인 배열 값을 사용하여 렌더링합니다. 사용자 목록을 출력하는 예시는 아래와 같습니다.

```php+HTML
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- example/resources/views/users/index.php -->
<div class="container">
    <h5>사용자 목록</h5>
    <div class="list-group">
<?php foreach ($users as $user): ?>
        <a href="/users/<?= $user->id ?>" class="list-group-item list-group-item-action">
            <div class="row">
                <div class="col-1"><?= $user->id ?></div>
                <div class="col-2"><?= $user->name ?></div>
                <div class="col-3"><?= $user->email ?></div>
                <div class="col-3"><?= $user->phone ?></div>
                <div class="col-3"><?= $user->created_at ?></div>
            </div>
        </a>
<?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php';?>
```

### Route XML

Route를 `routes.xm` 파일에서 정의하면 `public/index.php` 파일에서 `routes.xml` 파일을 Parse하여 `Foundation\Route` 클래스가 사용합니다. `routes.xml`의 구성은 아래와 같습니다. 

```xml
<?xml version="1.0" encoding="UTF-8"?>
<document>
    <!-- Route 정보 -->
    <routes baseUrl="/">
        <web>
            <route method="GET" url="/" controller="App\Controllers\HomeController@index" />
            <route method="GET" url="/info.html" controller="App\Controllers\HomeController@info" />
        </web>
    </routes>
    <!-- Error 발생 시 처리할 Handler 정보 -->
    <errors>
        <error code="404" name="Not Found" controller="App\Controllers\ErrorController::notFound" />
        <error code="405" name="Method Not Allowed" controller="App\Controllers\ErrorController::methodNotAllowed" />
    </errors>
</document>
```

#### Variables

Route는 URL에 사용자 입력 parameter를 받을 수 있으며, url에 `:value`와 같이 콜론(:) 으로 시작하는 변수명을 입력하여 사용합니다.

```xml
<route method="PUT" url="/users/:userId" controller="App\Controllers\UserController@update" />
```

위의 route의 `:userId`는 URL에 입력된 값이며(ex. /users/1 의 1) controller에서 해당 변수값을 아래와 같이 받아서 사용합니다.

```php
<?php
  
class UserController extends BaseController
{
  // 사용자 정보 수정. $userId 매개변수는 Route url에 정의된 :userId 항목.
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
}
```

### Error Handler

본 Framework에는 404, 405 Error Handler가 정의되어 있습니다. `routes.xml`에  `<errors>` node 아래 아래와 같이 정의하여 사용할 수 있습니다.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<document>
  <routes>
    <web>...</web>
  </routes>
	<errors>
        <error code="404" name="Not Found" controller="App\Controllers\ErrorController@notFound" />
        <error code="405" name="Method Not Allowed" controller="App\Controllers\ErrorController@methodNotAllowed" />
    </errors>
</document>
```

Error Controller는 아래와 같이 구현할 수 있습니다.

```php
<?php

namespace App\Controllers;

use Foundation\BaseController;

class ErrorController extends BaseController
{
    /**
     * 404 Not found
     *
     * @param $request      $_REQUEST
     */
    public function notFound($request)
    {
        return '404 Not Found';
    }

    /**
     * 405 Method not allowed
     *
     * @param $request      $_REQUEST
     */
    public function methodNotAllowed($request)
    {
        return '405 Method Not Allowed';
    }
}
```

### Crypto

bcrypt, AES를 적용하였습니다. 

#### bcrypt

PHP 5.3.x 버전에서는 password_hash, password_verify 함수를 지원하지 않기 때문에 아래의 모듈을 이용해  해당 함수가 없을 경우 모듈에 구현된 함수를 사용하도록 하였습니다.

- https://github.com/ircmaxell/password_compat 

#### AES

openssl 혹은 mcrypt 계열(최신 버전에서 deprecated)를 사용해서 구현하는 것이 일반적이나 둘 다 없을 경우를 대비해 아래의 순수 AES 구현 모듈을 적용하였습니다.

- https://github.com/phillipsdata/phpaes/blob/master/src/Aes.php

## Security

본 Framework에 구현되었고 예정인 보안 관련된 사항입니다.

#### SQL Injection

PDO의 Prepared Statement를 사용했습니다.

#### XSS

개발자가 수동으로 Controller에서 Sanitize 하여 사용할 수 있도록 하였습니다. `Foundation\BaseController` 의 `sanitizeStr`, `sanitizeRequest` 함수를 사용해 XSS Sanitize 할 수 있도록 하였습니다.

#### CSRF

해당 기능은 세션과 같이 구현되어야 하기 때문에 세션 구현과 같이 구현할 예정입니다.

#### 기타

- Rendering된 View 파일을 Sanitize 하여 공백, 탭, 주석 문을 제거하였습니다.

## Example

본 Framework에서는 간단한 사용자 정보의 CRUD 기능을 하는 예시 코드를 구현하였습니다. 데이터베이스 테이블 추가 후example` 디렉터리의 파일을 아래의 경로에 복사하여 사용 가능합니다.

1. `.env.example` 파일 복사하여 `.env` 파일 생성 후 데이터베이스 connection 정보 입력
2. test 데이터베이스 생성 후 `examples\test.sql` 파일을 import
3. `examples\app\Controllers\UserController.php` 파일을 `app\Controllers` 에 복사
4. `examples\app\Models\UserModel.php` 파일을 `app\Models` 에 복사
5. `examples\resources\views\users` 내 모든 파일을 `resources\views\users` 에 복사
6. `routes.xml.stub` 파일의 내용을 `routes.xml` 파일의 ` routes > web node 에 추가
7. `/users` URL에 접속 후 동작 확인

## Future Works

### CSRF

CSRF 구현은 Session이 필요하며 token 값 생성 과정 구현을 필요로 합니다. php 5.3.x 버전에서 랜덤한 token 사용에 mcrypt, openssl 은 별도의 플러그인이 필요하기 때문에 이를 포함해 UUID 등을 이용한 방법을 검토한 후 구현을 진행할 예정입니다.

- UUID: https://www.php.net/manual/en/function.uniqid.php#94959
- Token 생성: https://stackoverflow.com/questions/6287903/how-to-properly-add-cross-site-request-forgery-csrf-token-using-php
- Token 비교: https://github.com/indigophp/hash-compat

### Session

Session을 클래스로 구현해 적용할 예정입니다. 그러나 php 5.3.x에서 SessionHandlerInterface 를 지원하지 않기 때문에 아래와 같은 구현을 참조할 예정입니다.

- https://gist.github.com/asika32764/5fdb8137ca4af30254b9

## References

본 Framework의 Route는 아래의 내용을 참조해 구현되었습니다.

- https://steampixel.de/en/simple-and-elegant-url-routing-with-php/
- https://stackoverflow.com/questions/11722711/url-routing-regex-php

## License

MIT