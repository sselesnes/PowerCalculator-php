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
	  
<script>
// Збереження в БД
function saveToDb(fieldName, fieldValue) {
    const formData = new URLSearchParams();
    formData.append('field', fieldName);
    formData.append('value', fieldValue);

    fetch('engine/mysql_tables.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) console.error('Помилка збереження:', data.error);
    });
    calculateResults()
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

// Функція заповнення з пресетів
function setPreset(name, power, coeff) {
    document.getElementById('new-app-name').value = name;
    document.getElementById('new-app-power').value = power;
    document.getElementById('new-app-coeff').value = coeff;
    document.getElementById('presets-grid').style.display = 'none';
}

// Функція додавання приладу в базу
function addAppliance() {
    const name = document.getElementById('new-app-name').value;
    const power = parseInt(document.getElementById('new-app-power').value);
    const coeff = parseFloat(document.getElementById('new-app-coeff').value);

    if (!name) { alert('Введіть назву приладу'); return; }

    const formData = new URLSearchParams();
    formData.append('add_appliance', '1'); // Прапор для PHP
    formData.append('name', name);
    formData.append('rated_power', power);
    formData.append('peak_power', Math.round(power * coeff));
    formData.append('daily_hours', 5); // на майбутнє

    fetch('engine/mysql_tables.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // перерендити сторінку
        } else {
            alert('Помилка: ' + data.error);
        }
    })
    .catch(err => console.error('Помилка запиту:', err));
    calculateResults()
}

function calculateResults() {
    // 1. Дані АКБ
    const cap = parseFloat(document.getElementById('bat_capacity').value) || 0;
    const volt = parseFloat(document.getElementById('bat_voltage').value) || 0;
    const dod = 0.95; // 95% для LiFePO4

    // 2. Дані Інвертора
    const invPower = parseInt(document.getElementById('inv_power').value) || 0;
    const invEff = (parseFloat(document.getElementById('inv_eff').value) || 92) / 100;

    // 3. Збір приладів (парсимо Вати з тексту в списку)
    let totalActive = 0;
    let totalPeak = 0;
    const rows = document.querySelectorAll('.appliance-row');
    
    rows.forEach(row => {
        const meta = row.querySelector('.appliance-meta').innerText;
        const activeMatch = meta.match(/(\d+)\s*W/);
        const peakMatch = meta.match(/пік\s*(\d+)/);
        
        if (activeMatch) totalActive += parseInt(activeMatch[1]);
        if (peakMatch) totalPeak += parseInt(peakMatch[1]);
    });

    // --- РОЗРАХУНКИ ---

    // Ефективна ємність
    const effAh = cap * dod;
    const effKwh = (effAh * volt) / 1000;

    // Автономність (Ефективна ємність Вт·год / (Навантаження / ККД))
    let autonomyText = "∞";
    if (totalActive > 0) {
        const consumption = totalActive / invEff;
        const hours = (effAh * volt) / consumption;
        
        const h = Math.floor(hours);
        const m = Math.round((hours - h) * 60);
        autonomyText = h > 0 ? `${h}г ${m}хв` : `${m}хв`;
        if (hours > 100) autonomyText = "> 100г";
    }

    // Завантаженість інвертора
    const invLoadPercent = invPower > 0 ? Math.round((totalActive / invPower) * 100) : 0;

    // --- ВИВІД В HTML ---

    // Секція АКБ
    document.getElementById('res-cap-nominal').innerText = cap + ' Ah';
    document.getElementById('res-cap-dod').innerText = effAh.toFixed(1) + ' Ah';
    document.getElementById('res-eff-ah').innerText = effAh.toFixed(1) + ' Ah';
    document.getElementById('res-eff-kwh').innerText = effKwh.toFixed(2) + ' кВт·год';

    // Картки статистики
    document.getElementById('res-active-load').innerHTML = `${totalActive}<span class="stat-card-unit">W</span>`;
    document.getElementById('res-peak-load').innerHTML = `${totalPeak}<span class="stat-card-unit">W</span>`;
    document.getElementById('res-autonomy').innerHTML = `${autonomyText}<span class="stat-card-unit"></span>`;
    document.getElementById('res-inv-percent-card').innerHTML = `${invLoadPercent}<span class="stat-card-unit">%</span>`;

    // Прогрес-бар інвертора
    document.getElementById('res-inv-percent-bar').innerText = invLoadPercent + '%';
    const barFill = document.getElementById('inv-load-fill');
    barFill.style.width = Math.min(invLoadPercent, 100) + '%';
    
    // Колір бару в залежності від навантаження
    if (invLoadPercent > 90) barFill.style.background = 'var(--error)';
    else if (invLoadPercent > 70) barFill.style.background = 'var(--warning)';
    else barFill.style.background = 'var(--emerald)';

    // Підказка внизу
    document.getElementById('calc-hint').innerText = totalActive > 0 
        ? "Розрахунок базується на поточному навантаженні" 
        : "Додайте прилади для розрахунку";
}

function deleteAppliance(appId) { 
    const formData = new URLSearchParams();
    formData.append('delete_id', appId); // Ключ який прописали в mysql_tables.php

    fetch('engine/mysql_tables.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Видаляємо елемент з екрану без перезавантаження
            const row = document.getElementById('app-row-' + appId);
            if (row) row.remove();
            
            // Перераховуємо результати, бо навантаження змінилося
            calculateResults();
        } else {
            alert('Помилка видалення: ' + data.error);
        }
    })
    .catch(err => console.error('Помилка:', err));
}

function printReport() {
    // Отримуємо поточні значення результатів
    const activeLoad = document.getElementById('res-active-load').innerText;
    const peakLoad = document.getElementById('res-peak-load').innerText;
    const autonomy = document.getElementById('res-autonomy').innerText;
    const energy = document.getElementById('res-eff-kwh').innerText;
    const capacity = document.getElementById('res-eff-ah').innerText;
    
    // Отримуємо список приладів
    let appliancesHtml = '';
    document.querySelectorAll('.appliance-row').forEach(row => {
        const name = row.querySelector('.appliance-name').innerText;
        const meta = row.querySelector('.appliance-meta').innerText;
        appliancesHtml += `
            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                <span style="font-weight: bold;">${name}</span>
                <span style="color: #666;">${meta}</span>
            </div>`;
    });

    // Відкриваємо нове вікно
    const printWindow = window.open('', '_blank', 'width=800,height=900');
    
    // Формуємо вміст звіту
    const reportHtml = `
    <!DOCTYPE html>
    <html>
    <head>
        <title>Звіт PowerCalculator</title>
        <style>
            body { font-family: 'JetBrains Mono', monospace; color: #0f172a; padding: 40px; line-height: 1.6; }
            .header { border-bottom: 2px solid #f59e0b; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
            .h-title { font-size: 24px; font-weight: 900; }
            .h-date { font-size: 12px; color: #64748b; }
            .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
            .card { border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; }
            .card-label { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
            .card-value { font-size: 20px; font-weight: 800; color: #f59e0b; }
            .section-title { font-size: 14px; font-weight: 700; text-transform: uppercase; margin: 20px 0 10px; border-left: 4px solid #f59e0b; padding-left: 10px; }
            @media print { .no-print { display: none; } }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="h-title">PowerCalculator <span style="color:#f59e0b">Report</span></div>
            <div class="h-date">${new Date().toLocaleString('uk-UA')}</div>
        </div>

        <div class="section-title">Енергосистема</div>
        <div class="grid">
            <div class="card">
                <div class="card-label">Ефективна енергія</div>
                <div class="card-value">${energy}</div>
                <div style="font-size: 11px; color: #64748b;">${capacity} при DoD 95%</div>
            </div>
            <div class="card">
                <div class="card-label">Прогнозна автономність</div>
                <div class="card-value" style="color: #10b981;">${autonomy}</div>
            </div>
        </div>

        <div class="section-title">Навантаження</div>
        <div class="grid">
            <div class="card">
                <div class="card-label">Активне</div>
                <div class="card-value">${activeLoad}</div>
            </div>
            <div class="card">
                <div class="card-label">Пікове</div>
                <div class="card-value" style="color: #f97316;">${peakLoad}</div>
            </div>
        </div>

        <div class="section-title">Перелік підключених приладів</div>
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px;">
            ${appliancesHtml || '<p style="color:#94a3b8">Прилади не додані</p>'}
        </div>

        <div style="margin-top: 50px; text-align: center; border-top: 1px solid #eee; padding-top: 20px;" class="no-print">
            <button onclick="window.print()" style="padding: 10px 20px; background: #f59e0b; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Надіслати на друк</button>
        </div>
    </body>
    </html>`;

    printWindow.document.write(reportHtml);
    printWindow.document.close();
}

document.addEventListener('DOMContentLoaded', calculateResults);
document.addEventListener('DOMContentLoaded', updateCalculations);
</script>