<?php

require_once 'app.php';

require_login();

$state = get_state();
$switches = $db->query("SELECT * FROM switch ORDER BY id")->fetchAll();

?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>SmartHome Dashboard</title>
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
  </head>
  <body>
    <div class="dashboard">
      <a href="logout.php" class="logout">
        Odhlásenie
        <i class="fa fa-sign-out"></i>
      </a>
      <div class="dashboard-content">
        <h1>Smart Home</h1>
        <div class="last-sync">Posledná synchronizácia dát <span class="last-sync-time"><?= $state['date'] ? htmlspecialchars($state['date']) : '...' ?></span></div>
        <div class="current-value">
          <div class="temperature">
            <div class="label">
              <i class="fa fa-temperature-half"></i>
            </div>
            <div class="value"><?= number_format($state['temperature'], 1, '.', '') ?></div>
            <div class="unit">°C</div>
          </div>
          <div class="humidity">
            <div class="label">
              <i class="fa fa-droplet"></i>
            </div>
            <div class="value"><?= number_format($state['humidity'], 0, '.', '') ?></div>
            <div class="unit">%</div>
          </div>
        </div>
        <div class="switches">
          <?php foreach ($switches as $switch) { ?>
          <dl>
            <dt><i class="fa <?= htmlspecialchars($switch['icon']) ?>" style="font-size:40px"></i> <?= htmlspecialchars($switch['name']) ?></dt>
            <dd>
              <label class="switch">
                <input type="checkbox" value="<?= htmlspecialchars($switch['id']) ?>" <?= $switch['active'] ? ' checked="checked"' : '' ?> />
                <span></span>
              </label>
            </dd>
          </dl>
          <?php } ?>
        </div>
      </div>
    </div>
        
    <script src="/assets/plugins/jquery-3.6.4.min.js"></script>
    <script>
    (function() {
      const temperature = $('.temperature .value')
      const humidity = $('.humidity .value')
      const time = $('.last-sync-time')
      
      function init() {
        setInterval(refresh, 5000)
        $('input[type=checkbox]').on('change', update)
      }
      
      function refresh() {
        $.ajax({
          url: 'state.php',
          dataType: 'json',
          success: state => {
            if (state.temperature !== null) {
              temperature.text(state.temperature.toFixed(1))
              humidity.text(state.humidity.toFixed(0))
              time.text(state.date)
            }
          }
        })
      }
      
      function update() {
        var s = $(this)
        
        $.ajax({
          url: 'update.php',
          method: 'POST',
          dataType: 'json',
          data: {
            id: s.val(),
            active: s.is(':checked')
          }
        })
      }
      
      init()
    })()
    </script>
  </body>
</html>
