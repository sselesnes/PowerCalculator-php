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

                 <option value="LiFePO4" <?php echo isset(
                     $user_set["bat_type"]
                 ) && $user_set["bat_type"] == "LiFePO4"
                     ? "selected"
                     : ""; ?>>LiFePO4</option>
                <option value="AGM" <?php echo isset($user_set["bat_type"]) &&
                $user_set["bat_type"] == "AGM"
                    ? "selected"
                    : ""; ?>>AGM</option>
    
                </select>
              </div>
              <div class="field">
                <label for="bat_voltage">Напруга</label>
                <select class="select" id="bat_voltage" name="bat_voltage">

                  <option value="12" <?php echo isset(
                      $user_set["bat_voltage"]
                  ) && $user_set["bat_voltage"] == "12"
                      ? "selected"
                      : ""; ?>>12 V</option>
                  <option value="24" <?php echo isset(
                      $user_set["bat_voltage"]
                  ) && $user_set["bat_voltage"] == "24"
                      ? "selected"
                      : ""; ?>>24 V</option>
                  <option value="48" <?php echo isset(
                      $user_set["bat_voltage"]
                  ) && $user_set["bat_voltage"] == "48"
                      ? "selected"
                      : ""; ?>>48 V</option>
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
                    value="<?php echo isset($user_set["bat_capacity"])
                        ? $user_set["bat_capacity"]
                        : "105"; ?>"
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
                    value="<?php echo isset($user_set["bat_temp"])
                        ? $user_set["bat_temp"]
                        : "25"; ?>"
                  />
                  <span class="input-unit colored-temp">°C</span>
                </div>
              </div>
            </div>
            <div class="summary-row" style="margin-top: 14px">
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
                    value="<?php echo isset($user_set["inv_power"])
                        ? $user_set["inv_power"]
                        : "2000"; ?>"
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
                    value="<?php echo isset($user_set["inv_eff"])
                        ? $user_set["inv_eff"]
                        : "92"; ?>"
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
                    value="<?php echo isset($user_set["inv_peak"])
                        ? $user_set["inv_peak"]
                        : "4000"; ?>"
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
      <div class="cap-track"><div id="bar-nominal" class="cap-fill nominal" style="width: 100%"></div></div>
      <span class="cap-value dim" id="res-cap-nominal">0 Ah</span>
    </div>
    <div class="cap-row">
      <span class="cap-label">DoD (95%)</span>
      <div class="cap-track"><div id="bar-dod" class="cap-fill lifepo4" style="width: 95%"></div></div>
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

  <div class="stat-grid" style="margin-top: 14px">
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

  <div style="margin-top: 14px">
    <div class="load-bar-header">
      <span>Завантаженість інвертора</span>
      <span id="res-inv-percent-bar">0%</span>
    </div>
    <div class="load-bar-track">
      <div id="inv-load-fill" class="load-bar-fill" style="width: 0%"></div>
    </div>
  </div>

  <p id="calc-hint" style="text-align: center; font-size: 12px; color: var(--text-dim); padding: 8px 0;">
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
      <button class="btn btn-ghost" onclick="location.reload()">↺ Оновити</button>
      <button class="btn btn-primary" onclick="printReport()">Друкувати звіт</button>    
    </div>

    <div class="appliance-list" id="appliance-list">
      <?php if (empty($appliances)): ?>
        <p style="text-align: center; color: var(--text-dim); padding: 20px;">Список приладів порожній</p>
      <?php else: ?>
        <?php foreach ($appliances as $app): ?>
          <div class="appliance-row" id="app-row-<?= $app["id"] ?>">
            <button class="appliance-toggle">●</button>
            <div class="appliance-info">
              <div class="appliance-name"><?= htmlspecialchars(
                  $app["name"]
              ) ?></div>
              <div class="appliance-meta">
                <?= $app["rated_power"] ?> W · 
                пік <?= $app["peak_power"] ?> W · 
                ×<?= number_format(
                    $app["peak_power"] / ($app["rated_power"] ?: 1),
                    1
                ) ?>
              </div>
            </div>
          <button class="appliance-remove" onclick="deleteAppliance(<?php echo $app[
              "id"
          ]; ?>)">✕</button>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="add-form">
      <div class="add-form-header">
        <span class="add-form-title">+ Додати прилад</span>
        <button class="btn-outline" onclick="document.getElementById('presets-grid').style.display = 'grid'">Типові пристрої</button>
      </div>

      <div class="presets-grid" id="presets-grid" style="display: none">
        <button class="preset-btn" onclick="setPreset('Холодильник', 150, 5)">
            <span>Холодильник (150W, x5)</span>
        </button>
        <button class="preset-btn" onclick="setPreset('Роутер', 15, 1)">
            <span>Роутер (15W, x1)</span>
        </button>
      </div>

      <div style="margin-bottom: 8px">
        <input class="input" type="text" id="new-app-name" placeholder="Назва (напр. Телевізор)" />
      </div>

      <div class="add-form-row">
        <div class="input-wrap">
          <input class="input" type="number" id="new-app-power" value="100" min="1" />
          <span class="input-unit">W</span>
        </div>
        <div class="input-wrap">
          <input class="input" type="number" id="new-app-coeff" value="1" min="1" max="10" step="0.5" />
          <span class="input-unit">×</span>
        </div>
        <button class="btn btn-primary" onclick="addAppliance()">Додати</button>
      </div>
    </div>
  
</div>
</div>

<script src="/engine/app.js"></script>
<script>
document.addEventListener("DOMContentLoaded", calculateResults);
document.addEventListener("DOMContentLoaded", updateCalculations);

// EventListener 'change' на всі інпути та селекти в панелях налаштувань
document
  .querySelectorAll(".panel .select, .panel .input")
  .forEach((element) => {
    element.addEventListener("change", function () {      
      if (this.name) {
        saveToDb(this.name, this.value);
        updateCalculations();
      }
    });
  });
</script>
