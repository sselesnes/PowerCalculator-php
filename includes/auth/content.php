<?php
require_once ROOT_PATH . "/engine/mysql_tables.php"; ?>

<div class="main-grid">
        <!-- ліва колонка -->
        <div class="col left">
          <!-- Акумулятор -->
          <div class="panel">
            <div class="section-title">

              <h2>Акумулятор</h2>
              <div class="line"></div>
            </div>
            <div class="form-grid">
              <div class="field">
                <label for="bat_type">Тип</label>
                <select class="select" id="bat_type" name="bat_type">
                <option value="LiFePO4">LiFePO4</option>
                <option value="AGM">AGM</option>
                </select>
              </div>
              <div class="field">
                <label for="bat_voltage">Напруга</label>
                <select class="select" id="bat_voltage" name="bat_voltage">
                  <option value="12">12</option>
                  <option value="24">24</option>
                  <option value="48">48</option>
                  </select>
              </div>
              <div class="field">
                <label for="bat_capacity">Ємність</label>
                <div class="input-wrap">
                  <input
                    class="input"
                    type="number"
                    id="bat_capacity"
                    name="bat_capacity"                    
                    min="10"
                    max="2000"
                    step="5"
                    value="33"
                  />
                  <span class="input-unit">Ah</span>
                </div>
              </div>
              <div class="field">
                <label for="bat_temp">Температура</label>
                <div class="input-wrap">
                  <input
                    class="input"
                    type="number"
                    id="bat_temp"
                    name="bat_temp"                    
                    min="-30"
                    max="60"
                    value="33"
                  />
                  <span class="input-unit colored-temp">°C</span>
                </div>
              </div>
            </div>
            <div class="summary-row">
              <span class="label">Загальна ємність</span>
              <span class="value" id="total-capacity-display">2.40 кВт·год</span>
            </div>
          </div>

          <!-- Інвертор -->
          <div class="panel">
            <div class="section-title">
              <h2>Інвертор</h2>
              <div class="line"></div>
            </div>
            <div class="form-grid">
              <div class="field">
                <label for="inv_power">Номінальна потужність</label>
                <div class="input-wrap">
                  <input
                    class="input"
                    type="number"
                    id="inv_power"
                    name="inv_power"                    
                    min="100"
                    max="20000"
                    step="100"
                    value="33"
                  />
                  <span class="input-unit">W</span>
                </div>
              </div>
              <div class="field">
                <label for="inv_eff">ККД</label>
                <div class="input-wrap">
                  <input
                    class="input"
                    type="number"
                    id="inv_eff"
                    name="inv_eff"                    
                    min="50"
                    max="100"
                    step="0.5"
                    value="55"
                  />
                  <span class="input-unit">%</span>
                </div>
              </div>
              <div class="field">
                <label for="inv_peak">Пікове навантаження</label>
                <div class="input-wrap">
                  <input
                    class="input"
                    type="number"
                    id="inv_peak"
                    name="inv_peak"                    
                    min="100"
                    max="50000"
                    step="100"
                    value="111"
                  />
                  <span class="input-unit">W</span>
                </div>
              </div>
              <div class="losses-box">
                <div class="losses-label">Втрати інвертора</div>
                <div class="losses-value" id="inv_losses_display">−8.0%</div>
              </div>
            </div>
          </div>
        
          <!-- Результати -->
          <div class="panel">
  <div class="section-title">
    <h2>Результати</h2>
    <div class="line"></div>
  </div>

  <div class="capacity-section">
    <p class="capacity-title">Ефективна ємність АКБ</p>
    <div class="cap-row">
      <span class="cap-label">Номінал</span>
      <div class="cap-track"><div id="bar-nominal" class="cap-fill nominal"></div></div>
      <span class="cap-value dim" id="res-cap-nominal">0 Ah</span>
    </div>
    <div class="cap-row">
      <span class="cap-label">DoD (95%)</span>
      <div class="cap-track"><div id="bar-dod" class="cap-fill lifepo4" ></div></div>
      <span class="cap-value" id="res-cap-dod">0 Ah</span>
    </div>
    <div class="cap-footer">
      <span class="cap-footer-label">Ефективна ємність</span>
      <div class="cap-footer-values">
        <span id="res-eff-ah">0.0 Ah</span>
        <span class="cap-footer-dot">·</span>
        <span id="res-eff-kwh">0.00 кВт·год</span>
      </div>
    </div>
  </div>

  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-card-label">Активне навантаження</div>
      <div class="stat-card-value color-amber" id="res-active-load">
        0<span class="stat-card-unit">W</span>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-card-label">Пікове навантаження</div>
      <div class="stat-card-value color-orange" id="res-peak-load">
        0<span class="stat-card-unit">W</span>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-card-label">Автономність</div>
      <div class="stat-card-value color-emerald" id="res-autonomy">
        ∞<span class="stat-card-unit"></span>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-card-label">Навантаж. інвертора</div>
      <div class="stat-card-value color-emerald" id="res-inv-percent-card">
        0<span class="stat-card-unit">%</span>
      </div>
    </div>
  </div>
      <div class="load-bar-header">
      <span>Завантаженість інвертора</span>
      <span id="res-inv-percent-bar">0%</span>
    </div>
    <div class="load-bar-track">
      <div id="inv-load-fill" class="load-bar-fill"></div>
    </div>
  
  <p class="calc-hint" id="calc-hint">
    Додайте прилади для розрахунку
  </p>
</div>
</div>
        <!-- права колонка -->
<div class="col right">
  <div class="panel">
    <div class="section-title">
      <h2>Прилади</h2>
      <div class="line"></div>
      <button class="btn btn-ghost" onclick="location.reload()">Оновити</button>
      <button class="btn btn-primary" onclick="printReport()">Друкувати звіт</button>    
    </div>

<div class="appliance-list" id="appliance-list">
  <?php if (empty($appliances)): ?>
    <p>Список приладів порожній</p>
  <?php else: ?>
    <?php foreach ($appliances as $app): ?>
      <div class="appliance-row" id="app-row-<?= $app["id"] ?>">
        <button class="appliance-toggle">●</button>
        <div class="appliance-info">
          <div class="appliance-name"><?= htmlspecialchars(
              $app["name"]
          ) ?></div>
          <div class="appliance-meta">
            <?= $app["rated_power"] ?> W · пік <?= $app["peak_power"] ?> W · 
            ×<?= number_format(
                $app["peak_power"] / ($app["rated_power"] ?: 1),
                1
            ) ?>
          </div>
        </div>
        <button class="appliance-remove" data-id="<?= $app["id"] ?>">✕</button>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

    <div class="add-form">
  <div class="add-form-header">
    <span class="add-form-title">+ Додати прилад</span>
    <button class="btn-outline" id="show-presets">Типові пристрої</button>
  </div>

  <div class="presets-grid" id="presets-grid" style="display: none">
    <button class="preset-btn" data-name="Холодильник" data-power="150" data-coeff="5">
        <span>Холодильник (150W, x5)</span>
    </button>
    <button class="preset-btn" data-name="Роутер" data-power="15" data-coeff="1">
        <span>Роутер (15W, x1)</span>
    </button>
    <button class="preset-btn" data-name="Ноутбук" data-power="65" data-coeff="1">
        <span>Ноутбук (65W, x1)</span>
    </button>
    <button class="preset-btn" data-name="Лампа" data-power="10" data-coeff="1">
        <span>Лампа (10W, x1)</span>
    </button>
  </div>
  
    <input class="input" type="text" id="new-app-name" placeholder="Назва (напр. Телевізор)" />

  <div class="add-form-row">
    <div class="input-wrap">
      <input class="input" type="number" id="new-app-power" value="100" min="1" />
      <span class="input-unit">W</span>
    </div>
    <div class="input-wrap">
      <input class="input" type="number" id="new-app-coeff" value="1" min="1" max="10" step="0.5" />
      <span class="input-unit">×</span>
    </div>
    <button class="btn btn-primary" id="add-app-btn">Додати</button>
  </div>
</div>
</div>
</div>

<script>
window.userSettings = <?= json_encode($user_set) ?>;
</script>
<script src="/engine/app.js"></script>