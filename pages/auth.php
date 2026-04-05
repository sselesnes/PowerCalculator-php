<div class="middle">
   <p class="title">Особистий кабінет</p>
    <form method="post">
      <div class="row">
        <label>E-mail:</label>
        <input type="email" name="email" value="" id="email" placeholder="Введіть Ваш E-mail" />
      </div>
      <div class="row">
        <label>Пароль:</label>
        <input type="password" name="pass" value="" id="password" placeholder="Введіть Ваш пароль" />
      </div>
      <div class="row">
        <button class="btn btn-primary" name="sign">Увійти</button>
      </div>
    </form>

    <?php if (isset($_SESSION["error"])): ?>
      <div class="auth-err"><?php
      echo $_SESSION["error"];
      unset($_SESSION["error"]);
      ?></div>
    <?php endif; ?>
</div>