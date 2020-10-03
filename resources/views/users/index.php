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

    <h5>사용자 목록</h5>

    <div class="list-group">

<?php foreach ($users as $user): ?>
        <a href="/users/<?= $user->id ?>" class="list-group-item list-group-item-action">
            <div class="row">
                <div class="col-1">
                <?= $user->id ?>
                </div>
                <div class="col-2">
                    <?= $user->name ?>
                </div>
                <div class="col-3">
                    <?= $user->email ?>
                </div>
                <div class="col-3">
                    <?= preg_replace(
                        "/([0-9]{3})([0-9]{3,4})([0-9]{4})$/",
                        "\\1-\\2-\\3",
                        $user->phone
                    ) ?>
                </div>
                <div class="col-3">
                    <?= $user->created_at ?>
                </div>
            </div>
        </a>
<?php endforeach; ?>

    </div>

    <div class="mt-3">
        <a href="/users/create" class="btn btn-primary">추가</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php';
?>
