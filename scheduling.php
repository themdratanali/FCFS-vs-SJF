<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FCFS vs SJF Scheduler</title>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #f0f2f5;
      color: #333;
    }
    .container {
      max-width: 1000px;
      margin: 40px auto;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
    h2 {
      text-align: center;
      color: #007bff;
      margin-bottom: 30px;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .inline-input {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 12px;
    }
    label {
      font-weight: 600;
    }
    input[type="number"] {
      padding: 8px 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      width: 120px;
    }
    .process-inputs {
      display: flex;
      gap: 12px;
      align-items: center;
      margin-bottom: 12px;
    }
    input[type="submit"], .refresh-btn {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      width: fit-content;
      margin-top: 10px;
    }
    input[type="submit"]:hover, .refresh-btn:hover {
      background-color: #0056b3;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: #fdfdfd;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background-color: #007bff;
      color: white;
    }
    .result {
      margin-top: 20px;
      font-size: 18px;
      font-weight: bold;
      text-align: center;
      color: #007bff;
    }
    .buttons {
      display: flex;
      gap: 12px;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>FCFS vs SJF Scheduling</h2>

  <form method="post">
    <div class="inline-input">
      <label>Number of Processes:</label>
      <input type="number" name="n" min="1" required value="<?= $_POST['n'] ?? '' ?>">
    </div>

    <?php
      if (!empty($_POST['n']) || isset($_POST['go'])) {
        $n = intval($_POST['n']);
        $arr = $_POST['arrival'] ?? [];
        $bur = $_POST['burst'] ?? [];

        for ($i = 0; $i < $n; $i++) {
          $a = $arr[$i] ?? '';
          $b = $bur[$i] ?? '';
          echo "<div class='process-inputs'>
                  <strong>P" . ($i + 1) . "</strong>
                  <label>Arrival:</label>
                  <input type='number' name='arrival[]' value='$a' required>
                  <label>Burst:</label>
                  <input type='number' name='burst[]' value='$b' required>
                </div>";
        }
        echo "<div class='buttons'>
                <input type='submit' name='go' value='Calculate'>
                <button type='button' class='refresh-btn' onclick='window.location.href=window.location.href'>Refresh</button>
              </div>";
      }
    ?>
  </form>

  <?php
    function schedule($arrival, $burst, $algo) {
      $n = count($arrival);
      $processes = [];
      for ($i = 0; $i < $n; $i++) {
        $processes[] = ['arrival' => $arrival[$i], 'burst' => $burst[$i], 'index' => $i];
      }
      usort($processes, fn($a, $b) => $a['arrival'] <=> $b['arrival']);
      $time = 0;
      $done = [];
      $waitingSum = 0;

      while (!empty($processes)) {
        $ready = array_filter($processes, fn($p) => $p['arrival'] <= $time);
        if ($ready) {
          if ($algo === 'SJF') {
            usort($ready, fn($a, $b) => $a['burst'] <=> $b['burst']);
          }
          $p = $ready[0];
          $index = array_search($p, $processes);
          $start = $time;
          $finish = $start + $p['burst'];
          $waiting = $start - $p['arrival'];
          $waitingSum += $waiting;
          $done[] = [
            'arrival' => $p['arrival'], 'burst' => $p['burst'],
            'start' => $start, 'finish' => $finish, 'waiting' => $waiting
          ];
          $time = $finish;
          unset($processes[$index]);
          $processes = array_values($processes);
        } else {
          $time = $processes[0]['arrival'];
        }
      }

      return ['table' => $done, 'avg' => $waitingSum / $n];
    }

    if (isset($_POST['go'])) {
      $arrival = $_POST['arrival'];
      $burst = $_POST['burst'];

      $fcfs = schedule($arrival, $burst, 'FCFS');
      $sjf = schedule($arrival, $burst, 'SJF');

      function showTable($title, $data) {
        echo "<h3>$title</h3>";
        echo "<table><tr><th>Arrival</th><th>Burst</th><th>Start</th><th>Finish</th><th>Waiting</th></tr>";
        foreach ($data['table'] as $row) {
          echo "<tr><td>{$row['arrival']}</td><td>{$row['burst']}</td><td>{$row['start']}</td><td>{$row['finish']}</td><td>{$row['waiting']}</td></tr>";
        }
        echo "</table>";
        echo "<div class='result'>Average Waiting Time: " . number_format($data['avg'], 2) . "</div>";
      }

      showTable("FCFS Scheduling", $fcfs);
      showTable("SJF Scheduling", $sjf);

      $better = $fcfs['avg'] < $sjf['avg'] ? "FCFS" : "SJF";
      echo "<div class='result'>$better performs better based on lower Average Waiting Time.</div>";
    }
  ?>
</div>

</body>
</html>