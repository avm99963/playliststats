<?php
require_once("core.php");
if (!isset($_GET["user"]) || !isset($_GET["playlist"])) {
  header("Location: index.php");
  exit();
}
$playlist = getPlaylistStats($_GET["user"], $_GET["playlist"]);
if ($playlist === false) {
  exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>Playlist stats – <?=$playlist["details"]["name"]?></title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700|Roboto+Mono:400|Product+Sans:700">
    <link rel="stylesheet" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css">
  </head>
  <body>
    <div class="centered mdc-elevation--z4">
      <h1><?=$playlist["details"]["name"]?> by <?=$playlist["details"]["owner"]["display_name"]?></h1>
      <div id="chart"></div>
    </div>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <script>
    mdc.autoInit();

    function drawChart() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'User');
      data.addColumn('number', 'Tracks added');
      data.addRows([
        <?php
        $write = [];
        foreach ($playlist["people"] as $person => $count) {
          $write[] = "['".addslashes($person)."', ".(int)$count."]";
        }
        echo implode(",", $write);
        ?>
      ]);

      var chart = new google.visualization.PieChart(document.querySelector("#chart"));
      var options = {
        chartArea: {
          width: '100%',
          height: '100%',
          left:10,
          right:10,
          bottom:10,
          top:10
        },
        fontName: "Roboto"
      };
      chart.draw(data, options);
    }

    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    </script>
  </body>
</html>
