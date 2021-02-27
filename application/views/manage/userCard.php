<div class="card d-flex w100">

  <div class="card-fixed btn-box">
    <?php if($user["id"] === $_SESSION["user"]): ?>
      <a class="btn primary" href="user/mypage">
        マイページ
      </a>
    <?php endif; ?>
  </div>

  <!-- Left-Section-->
  <div class="card-left w25">

    <!-- プロフィールアイコン -->
    <div class="card-section">
      <p class="card-label">
        プロフィールアイコン
      </p>
      <?php if(is_null($user["icon_url"])): ?>
        <p class="card-value">ユーザアイコン未設定です</p>
      <?php else: ?>
        <p class="card-value">
          <img src="<?php echo $user["icon_url"]; ?>">
        </p>
      <?php endif; ?>
    </div><!-- /プロフィールアイコン -->

  </div><!-- /Left-Section-->

  <!-- Right-Section-->
  <div class="card-right w75">

    <!-- ユーザ名 -->
    <div class="card-section">
      <p class="card-label">
        ユーザ名：
      </p>
      <p class="card-value">
        <?php echo $user["name"]; ?>
      </p>
    </div><!-- /ユーザ名 -->

    <!-- メールアドレス -->
    <div class="card-section">
      <p class="card-label">
        メールアドレス：
      </p>
      <p class="card-value">
        <?php echo $user["email"]; ?>
      </p>
    </div><!-- /メールアドレス -->

    <!-- 自己紹介 -->
    <div class="card-section">
      <p class="card-label">
        自己紹介：
      </p>
      <p class="card-value">
        <?php echo $user["about"]; ?>
      </p>
    </div><!-- /自己紹介 -->

  </div><!-- /Right-Section-->

</div>