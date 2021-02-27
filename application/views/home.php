<body id="home">
  <div class="main-container w75 text-center">
    <h1>ホーム</h1>
    <?php if($doneRegister): ?>
      <p class="text-success">
        ユーザの新規登録が完了しました。
      </p>
    <?php endif; ?>
    <div class="w50 d-flex justify-between mx-auto my-100">
      <div class="btn-box">
        <a class="btn primary" href="user/register">
          新規登録
        </a>
      </div>
      <div class="btn-box">
        <a class="btn secondary" href="user/login">
          ログイン
        </a>
      </div>
    </div>
  </div>
</body>