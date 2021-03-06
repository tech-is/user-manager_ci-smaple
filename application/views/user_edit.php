<body id="manage">
  <div class="main-container w75 text-center">
    <div class="d-flex container-fixed">
      <a class="btn secondary mr-12" href="/user-manager/user/manage">
        ホーム
      </a>
      <a class="btn info" href="/user-manager/user/logout">
        ログアウト
      </a>
    </div>
    <h1>ユーザ編集</h1>
    <form action="" method="POST">
      <div class="d-flex">
          <?php include(__DIR__."/manage/edit/userCard.php") ?>
      </div>
    </form>
  </div>
</body>