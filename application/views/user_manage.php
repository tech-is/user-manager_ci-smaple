<body id="manage">
  <div class="main-container w60 text-center">
    <div class="container-fixed">
      <a class="btn info" href="/user-manager/user/logout">
        ログアウト
      </a>
    </div>
    <h1>マネージ画面</h1>
    <?php if($doneDelete): ?>
      <p class="text-success">
        ユーザの削除が完了しました。
      </p>
    <?php endif; ?>
    <div class="d-flex">
      <?php foreach($users as $user): ?>
        <?php include(__DIR__."/manage/home/userCard.php") ?>
      <?php endforeach; ?>
    </div>
  </div>
</body>