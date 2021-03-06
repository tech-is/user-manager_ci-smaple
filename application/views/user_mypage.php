<body id="manage">
  <div class="main-container w60 text-center">
    <div class="d-flex container-fixed">
      <a class="btn secondary mr-12" href="/user-manager/user/manage">
        ホーム
      </a>
      <a class="btn info" href="/user-manager/user/logout">
        ログアウト
      </a>
    </div>
    <h1>マイページ</h1>
    <div class="d-flex">
      <?php include(__DIR__."/manage/mypage/userCard.php") ?>
    </div>
  </div>
</body>