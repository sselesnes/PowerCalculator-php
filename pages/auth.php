<div id="middle">
  <?php if (isset($_SESSION["error"])): ?>
    <div style="color: red;"><?php
    echo $_SESSION["error"];
    unset($_SESSION["error"]);
    ?></div>
  <?php endif; ?>

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
        <button name="sign">Увійти</button>
      </div>
    </form>
</div>