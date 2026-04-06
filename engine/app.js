// Збереження в БД
function saveToDb(fieldName, fieldValue) {
  const formData = new URLSearchParams();
  formData.append("field", fieldName);
  formData.append("value", fieldValue);

  fetch("engine/mysql_tables.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (!data.success) console.error("Помилка збереження:", data.error);
    });
  calculateResults();
}

function updateCalculations() {
  // Отримуємо значення з полів
  const voltage = parseFloat(document.getElementById("bat_voltage").value);
  const capacity = parseFloat(document.getElementById("bat_capacity").value);
  const efficiency = parseFloat(document.getElementById("inv_eff").value);

  // Рахуємо втрати
  if (!isNaN(efficiency)) {
    const losses = 100 - efficiency;
    // Виводимо з мінусом та знаком %
    document.getElementById("inv_losses_display").innerText =
      "−" + losses.toFixed(1) + "%";
  }

  // Рахуємо реальну (ефективну) енергію з урахуванням ККД
  // (V * Ah * Efficiency) / 100 / 1000
  if (!isNaN(voltage) && !isNaN(capacity) && !isNaN(efficiency)) {
    const totalKwh = (voltage * capacity) / 1000;
    const effectiveKwh = totalKwh * (efficiency / 100);

    // Результат у форматі "0.00"
    document.getElementById("total-capacity-display").innerText =
      effectiveKwh.toFixed(2) + " кВт·год";
  }
}

// Функція заповнення з пресетів
function setPreset(name, power, coeff) {
  document.getElementById("new-app-name").value = name;
  document.getElementById("new-app-power").value = power;
  document.getElementById("new-app-coeff").value = coeff;
  document.getElementById("presets-grid").style.display = "none";
}

// Функція додавання приладу в базу
function addAppliance() {
  const name = document.getElementById("new-app-name").value;
  const power = parseInt(document.getElementById("new-app-power").value);
  const coeff = parseFloat(document.getElementById("new-app-coeff").value);

  if (!name) {
    alert("Введіть назву приладу");
    return;
  }

  const formData = new URLSearchParams();
  formData.append("add_appliance", "1"); // Прапор для PHP
  formData.append("name", name);
  formData.append("rated_power", power);
  formData.append("peak_power", Math.round(power * coeff));
  formData.append("daily_hours", 5); // на майбутнє

  fetch("engine/mysql_tables.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        location.reload(); // перерендити сторінку
      } else {
        alert("Помилка: " + data.error);
      }
    })
    .catch((err) => console.error("Помилка запиту:", err));
  calculateResults();
}

function calculateResults() {
  // Дані АКБ
  const cap = parseFloat(document.getElementById("bat_capacity").value) || 0;
  const volt = parseFloat(document.getElementById("bat_voltage").value) || 0;
  const dod = 0.95; // 95% для LiFePO4

  // Дані Інвертора
  const invPower = parseInt(document.getElementById("inv_power").value) || 0;
  const invEff =
    (parseFloat(document.getElementById("inv_eff").value) || 92) / 100;

  // Збір приладів (парсимо Вати з тексту в списку)
  let totalActive = 0;
  let totalPeak = 0;
  const rows = document.querySelectorAll(".appliance-row");

  rows.forEach((row) => {
    const meta = row.querySelector(".appliance-meta").innerText;
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
  const invLoadPercent =
    invPower > 0 ? Math.round((totalActive / invPower) * 100) : 0;

  // --- ВИВІД В HTML ---
  // Секція АКБ
  document.getElementById("res-cap-nominal").innerText = cap + " Ah";
  document.getElementById("res-cap-dod").innerText = effAh.toFixed(1) + " Ah";
  document.getElementById("res-eff-ah").innerText = effAh.toFixed(1) + " Ah";
  document.getElementById("res-eff-kwh").innerText =
    effKwh.toFixed(2) + " кВт·год";

  // Картки статистики
  document.getElementById("res-active-load").innerHTML =
    `${totalActive}<span class="stat-card-unit">W</span>`;
  document.getElementById("res-peak-load").innerHTML =
    `${totalPeak}<span class="stat-card-unit">W</span>`;
  document.getElementById("res-autonomy").innerHTML =
    `${autonomyText}<span class="stat-card-unit"></span>`;
  document.getElementById("res-inv-percent-card").innerHTML =
    `${invLoadPercent}<span class="stat-card-unit">%</span>`;

  // Прогрес-бар інвертора
  document.getElementById("res-inv-percent-bar").innerText =
    invLoadPercent + "%";
  const barFill = document.getElementById("inv-load-fill");
  barFill.style.width = Math.min(invLoadPercent, 100) + "%";

  // Колір бару в залежності від навантаження
  if (invLoadPercent > 90) barFill.style.background = "var(--error)";
  else if (invLoadPercent > 70) barFill.style.background = "var(--warning)";
  else barFill.style.background = "var(--emerald)";

  // Підказка внизу
  document.getElementById("calc-hint").innerText =
    totalActive > 0
      ? "Розрахунок базується на поточному навантаженні"
      : "Додайте прилади для розрахунку";
}

function deleteAppliance(appId) {
  const formData = new URLSearchParams();
  formData.append("delete_id", appId); // Ключ який прописали в mysql_tables.php

  fetch("engine/mysql_tables.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Видаляємо елемент з екрану без перезавантаження
        const row = document.getElementById("app-row-" + appId);
        if (row) row.remove();

        // Перераховуємо результати, бо навантаження змінилося
        calculateResults();
      } else {
        alert("Помилка видалення: " + data.error);
      }
    })
    .catch((err) => console.error("Помилка:", err));
}

function printReport() {
  // Отримуємо поточні значення результатів
  const activeLoad = document.getElementById("res-active-load").innerText;
  const peakLoad = document.getElementById("res-peak-load").innerText;
  const autonomy = document.getElementById("res-autonomy").innerText;
  const energy = document.getElementById("res-eff-kwh").innerText;
  const capacity = document.getElementById("res-eff-ah").innerText;

  // Отримуємо список приладів
  let appliancesHtml = "";
  document.querySelectorAll(".appliance-row").forEach((row) => {
    const name = row.querySelector(".appliance-name").innerText;
    const meta = row.querySelector(".appliance-meta").innerText;
    appliancesHtml += `
            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                <span style="font-weight: bold;">${name}</span>
                <span style="color: #666;">${meta}</span>
            </div>`;
  });

  // Відкриваємо нове вікно
  const printWindow = window.open("", "_blank", "width=800,height=900");

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
            <div class="h-date">${new Date().toLocaleString("uk-UA")}</div>
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

document.addEventListener("DOMContentLoaded", () => {
  // Автозаповнення інпутів
  const settings = window.userSettings || {};
  for (let key in settings) {
    // Шукаємо елемент за атрибутом name (який збігається з ключем у БД)
    let element = document.getElementsByName(key)[0];
    if (element) {
      element.value = settings[key];
    }
  }

  // Слухачі інпутів
  document
    .querySelectorAll(".panel .select, .panel .input")
    .forEach((element) => {
      element.addEventListener("change", function () {
        if (this.name) {
          saveToDb(this.name, this.value);
          updateCalculations();
          calculateResults();
        }
      });
    });

  // Показуємо сітку пресетів
  const showPresetsBtn = document.getElementById("show-presets");
  const presetsGrid = document.getElementById("presets-grid");

  if (showPresetsBtn && presetsGrid) {
    showPresetsBtn.addEventListener("click", () => {
      presetsGrid.style.display =
        presetsGrid.style.display === "none" ? "grid" : "none";
    });
  }

  // Вибір пресета
  if (presetsGrid) {
    presetsGrid.addEventListener("click", (e) => {
      const btn = e.target.closest(".preset-btn");
      if (btn) {
        // Використовуємо dataset для зручного доступу до data-атрибутів
        document.getElementById("new-app-name").value = btn.dataset.name;
        document.getElementById("new-app-power").value = btn.dataset.power;
        document.getElementById("new-app-coeff").value = btn.dataset.coeff;

        // Ховаємо сітку після вибору
        presetsGrid.style.display = "none";
      }
    });
  }

  // Слухач для додавання приладів
  const addAppBtn = document.getElementById("add-app-btn");
  if (addAppBtn) {
    addAppBtn.addEventListener("click", addAppliance);
  }

  // Слухач для видалення приладів
  const applianceList = document.getElementById("appliance-list");
  if (applianceList) {
    applianceList.addEventListener("click", function (e) {
      const removeBtn = e.target.closest(".appliance-remove");
      if (removeBtn) {
        deleteAppliance(removeBtn.getAttribute("data-id"));
      }
    });
  }

  // Перший розрахунок (всі поля вже заповнені)
  updateCalculations();
  calculateResults();
});
