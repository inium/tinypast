<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container">

    <!-- breadcrumb -->
    <div class="d-flex justify-content-between">
        <div>
            <h4>User</h4>
        </div>
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Home</li>
                    <li class="breadcrumb-item" aria-current="page">User</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- contents -->

    <h5>사용자 정보</h5>

    <div class="mt-3">

        <dt>이름</dt>
        <dd><?= $user->name ?></dd>

        <dt>이메일</dt>
        <dd><?= $user->email ?></dd>

        <dt>전화번호</dt>
        <dd>
            <?= preg_replace(
                "/([0-9]{3})([0-9]{3,4})([0-9]{4})$/",
                "\\1-\\2-\\3",
                $user->phone
            ) ?>
        </dd>

        <dt>메모</dt>
        <dd><?= $user->memo ?></dd>

        <dt>생성일</dt>
        <dd><?= $user->created_at ?></dd>
    </div>

    <div class="mt-5">
        <ul class="list-inline">
            <li class="list-inline-item">
                <a href="/users/<?= $user->id ?>/edit" class="btn btn-warning">수정</a>
            </li>
            <li class="list-inline-item">
                <form action="/users/<?= $user->id ?>" method="POST">
                    <input type="hidden" name="_method" value="delete">
                    <input type="submit" class="btn btn-danger" value="삭제">
                </form>
            </li>
        </ul>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php';
?>
