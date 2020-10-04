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
    <div class="list-group">

        <h5>사용자 추가</h5>
        <form action="/users" method="POST">

            <div class="row">
                <div class="col-5">

                    <!-- 이름 -->
                    <div class="form-group">
                        <label for="userName">Name</label>
                        <input type="text" name="user_name" class="form-control" id="userName">
                    </div>

                    <!-- 이메일 주소 -->
                    <div class="form-group">
                        <label for="userEmail">Email address</label>
                        <input type="email" name="user_email" class="form-control" id="userEmail">
                    </div>

                    <!-- 전화번호 -->
                    <div class="form-group">
                        <label for="userPhone">Phone</label>
                        <input type="text" name="user_phone" class="form-control" id="userPhone">
                    </div>

                    <!-- 메모 -->
                    <div class="form-group">
                        <label for="userMemo">Memo</label>
                        <input type="text" name="user_memo" class="form-control" id="userMemo">
                    </div>

                </div>



            </div>



            <input type="submit" class="btn btn-primary" value="저장">
        </form>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php';
?>
