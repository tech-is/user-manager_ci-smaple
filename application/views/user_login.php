<body>
  <h1>ログイン</h1>
  <p class="text-error"><?php echo $errorMessage; ?></p>
  <form method="POST">
    <div class="inputGroup">
      <label for="email">
        メールアドレス：
        <input type="email" id="email" name="email" required>
      </label>
    </div>
    <div class="inputGroup">
      <label for="password">
        パスワード：
        <input type="password" id="password" name="password" required>
      </label>
    </div>
    <div class="inputGroup">
      <input type="submit" id="login" name="login" value="ログイン">
    </div>
  </form>
</body>