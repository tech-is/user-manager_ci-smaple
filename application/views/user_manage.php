<body id="manage">
  <div class="main-container w75 text-center">
    <h1>マネージ画面</h1>
    <div class="d-flex">
      <?php foreach($users as $user): ?>
        <?php include(__DIR__."/manage/userCard.php") ?>
      <?php endforeach; ?>
    </div>
  </div>
</body>