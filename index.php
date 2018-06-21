<?php
require_once("core.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>Playlist stats</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700|Roboto+Mono:400|Product+Sans:700">
    <link rel="stylesheet" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css">
  </head>
  <body>
    <div class="centered mdc-elevation--z4">
      <h1>Playlist Stats for Spotify</h1>
      <form action="playlist.php" method="GET">
        <div class="mdc-text-field" data-mdc-auto-init="MDCTextField">
          <input type="text" id="user_id" name="user" class="mdc-text-field__input">
          <label class="mdc-floating-label" for="user_id">User ID</label>
          <div class="mdc-line-ripple"></div>
        </div>
        <br>
        <div class="mdc-text-field" data-mdc-auto-init="MDCTextField">
          <input type="text" id="playlist_id" name="playlist" class="mdc-text-field__input">
          <label class="mdc-floating-label" for="playlist_id">Playlist ID</label>
          <div class="mdc-line-ripple"></div>
        </div>
        <br>
        <button class="mdc-button mdc-button--raised button-margin">Submit</button>
      </form>
    </div>
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <script>
    mdc.autoInit();
    </script>
  </body>
</html>
