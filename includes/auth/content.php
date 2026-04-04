<?php
$u_id = $_SESSION["user"]["id"];
// Використовуємо *, щоб отримати всі налаштування
$res = $db->query("SELECT * FROM user_settings WHERE user_id = $u_id");
$user_set = $res->fetch_assoc();

// Якщо запису в базі ще немає, створюємо порожній масив, щоб не було помилок
if (!$user_set) {
    $user_set = [];
}
?>

<div class="main-grid">
        <!-- ліва колонка -->
        <div class="col">
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
                <div class="cap-track"><div class="cap-fill nominal"></div></div>
                <span class="cap-value dim">100 Ah</span>
              </div>
              <div class="cap-row">
                <span class="cap-label">DoD (95%)</span>
                <div class="cap-track"><div class="cap-fill lifepo4"></div></div>
                <span class="cap-value">95 Ah</span>
              </div>
              <div class="cap-footer">
                <span class="cap-footer-label">Ефективна ємність</span>
                <div class="cap-footer-values">
                  <span>95.0 Ah</span>
                  <span class="cap-footer-dot">·</span>
                  <span>2.28 кВт·год</span>
                </div>
              </div>
            </div>

            <div class="stat-grid" style="margin-top: 14px">
              <div class="stat-card">
                <div class="stat-card-label">Активне навантаження</div>
                <div class="stat-card-value color-amber">
                  0<span class="stat-card-unit">W</span>
                </div>
              </div>
              <div class="stat-card">
                <div class="stat-card-label">Пікове навантаження</div>
                <div class="stat-card-value color-orange">
                  0<span class="stat-card-unit">W</span>
                </div>
              </div>
              <div class="stat-card">
                <div class="stat-card-label">Автономність</div>
                <div class="stat-card-value color-emerald">
                  ∞<span class="stat-card-unit"></span>
                </div>
              </div>
              <div class="stat-card">
                <div class="stat-card-label">Навантаж. інвертора</div>
                <div class="stat-card-value color-emerald">
                  0<span class="stat-card-unit">%</span>
                </div>
              </div>
            </div>

            <div style="margin-top: 14px">
              <div class="load-bar-header">
                <span>Завантаженість інвертора</span>
                <span>0%</span>
              </div>
              <div class="load-bar-track">
                <div class="load-bar-fill" style="width: 0%"></div>
              </div>
            </div>

            <p
              style="
                text-align: center;
                font-size: 12px;
                color: var(--text-dim);
                padding: 8px 0;
              "
            >
              Додайте прилади для розрахунку
            </p>
          </div>
        </div>

        <!-- права колонка -->
        <div class="col">
          <div class="panel">
            <div class="section-title">
              <h2>Прилади</h2>
              <div class="line"></div>
                <button class="btn btn-ghost">↺ Скинути</button>
                <button class="btn btn-primary">Роздрукувати звіт</button>    
            </div>

            <div class="appliance-list" id="appliance-list">
              <div class="appliance-row">
                <button class="appliance-toggle">●</button>
                <div class="appliance-info">
                  <div class="appliance-name">Холодильник</div>
                  <div class="appliance-meta">150 W · пік 750 W · ×5</div>
                </div>
                <button class="appliance-remove">✕</button>
              </div>

              <div class="appliance-row" style="opacity: 0.5; background: var(--surface-d)">
                <button
                  class="appliance-toggle"
                  style="background: var(--border); color: var(--text-dim)"
                >
                  ○
                </button>
                <div class="appliance-info">
                  <div class="appliance-name">Ноутбук</div>
                  <div class="appliance-meta">65 W · пік 65 W · ×1</div>
                </div>
                <button class="appliance-remove">✕</button>
              </div>
            </div>

            <div class="add-form">
              <div class="add-form-header">
                <span class="add-form-title">+ Додати прилад</span>
                <button class="btn-outline">Типові пристрої</button>
              </div>

              <div class="presets-grid" id="presets-grid" style="display: none">
                <button class="preset-btn">
                  <span>
                    <span class="preset-name">Холодильник</span>
                    <span class="preset-meta">150 W · ×5</span>
                  </span>
                </button>
                <button class="preset-btn">
                  <span class="preset-icon">📡</span>
                  <span>
                    <span class="preset-name">Роутер + ONU</span>
                    <span class="preset-meta">15 W · ×1</span>
                  </span>
                </button>
                <button class="preset-btn">
                  <span>
                    <span class="preset-name">Ноутбук</span>
                    <span class="preset-meta">65 W · ×1</span>
                  </span>
                </button>
                <button class="preset-btn">
                  <span>
                    <span class="preset-name">Освітлення</span>
                    <span class="preset-meta">40 W · ×1</span>
                  </span>
                </button>
              </div>

              <div style="margin-bottom: 8px">
                <input
                  class="input"
                  type="text"
                  id="app-name"
                  name="app-name"
                  placeholder="Назва (напр. Телевізор)"
                />
              </div>

              <div class="add-form-row">
                <div class="input-wrap">
                  <input
                    class="input"
                    type="number"
                    id="app-power"
                    name="app-power"
                    value="100"
                    min="1"
                  />
                  <span class="input-unit">W</span>
                </div>
                <div class="input-wrap">
                  <input
                    class="input"
                    type="number"
                    id="app-start-coeff"
                    name="app-start-coeff"
                    value="1"
                    min="1"
                    max="10"
                    step="0.5"
                  />
                  <span class="input-unit">×</span>
                </div>
                <button class="btn btn-primary">Додати</button>
              </div>
            </div>
          </div>
        </div>
      </div>
	  
<script>
// Збереження в БД
function saveToDb(fieldName, fieldValue) {
    const formData = new URLSearchParams();
    formData.append('field', fieldName);
    formData.append('value', fieldValue);

    fetch('engine/save_settings.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) console.error('Помилка збереження:', data.error);
    });
}

// EventListener 'change' на всі інпути та селекти в панелях налаштувань
document.querySelectorAll('.panel .select, .panel .input').forEach(element => {
    element.addEventListener('change', function() {        
        saveToDb(this.name, this.value);        
        updateCalculations(); 
    });
});

function updateCalculations() {
    // Отримуємо значення з полів
    const voltage = parseFloat(document.getElementById('bat_voltage').value);
    const capacity = parseFloat(document.getElementById('bat_capacity').value);
    const efficiency = parseFloat(document.getElementById('inv_eff').value);

    // Рахуємо втрати 
    if (!isNaN(efficiency)) {
        const losses = 100 - efficiency;
        // Виводимо з мінусом та знаком %
        document.getElementById('inv_losses_display').innerText = '−' + losses.toFixed(1) + '%';
    }

    // Рахуємо реальну (ефективну) енергію з урахуванням ККД
    // (V * Ah * Efficiency) / 100 / 1000
    if (!isNaN(voltage) && !isNaN(capacity) && !isNaN(efficiency)) {
        const totalKwh = (voltage * capacity) / 1000;
        const effectiveKwh = totalKwh * (efficiency / 100);

        // Результат у форматі "0.00"
        document.getElementById('total-capacity-display').innerText = effectiveKwh.toFixed(2) + ' кВт·год';
    }
}

document.addEventListener('DOMContentLoaded', updateCalculations);
</script>