<body>
  <h1>ユーザ情報を作成するページです</h1>
  <p class="errorMessage"><?php echo $errorMessage; ?></p>
  <form method="POST">
    <div class="inputGroup">
      <label for="email">
        ログインメールアドレス：
        <input type="email" id="email" name="email" required>
      </label>
    </div>
    <div class="inputGroup">
      <label for="password">
        ログインパスワード：
        <input type="password" id="password" name="password" required>
      </label>
    </div>
    <div class="inputGroup">
      <input type="submit" id="register" name="register" value="新規登録">
    </div>
  </form>
</body>