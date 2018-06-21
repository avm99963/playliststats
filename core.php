<?php
require_once("config.php");
session_start();

function request($url, $method="GET", $params="", $headers=[]) {
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);
  if ($method == "POST") {
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
  }

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);


  $response = curl_exec($ch);

  curl_close($ch);

  $json = json_decode($response, true);

  return (json_last_error() == JSON_ERROR_NONE ? $json : false);
}

function getToken() {
  global $conf;
  $auth = base64_encode($conf["client_id"].":".$conf["client_secret"]);
  $json = request("https://accounts.spotify.com/api/token", "POST", "grant_type=client_credentials", ["Content-Type: application/x-www-form-urlencoded", "Authorization: Basic ".$auth]);
  return ($json !== false && isset($json["access_token"]) ? $json["access_token"] : false);
}

function getPlaylistStats($user, $playlist) {
  $token = getToken();
  if ($token === false) {
    echo "Couldn't get token<br>";
    return false;
  }

  $playlistjson = request("https://api.spotify.com/v1/users/".urlencode($user)."/playlists/".urlencode($playlist)."?fields=".urlencode("name,owner.display_name"), "GET", "", ["Authorization: Bearer ".$token]);

  if ($playlistjson === false || !isset($playlistjson["name"])) {
    echo "This playlist doesn't exist<br>";
    return false;
  }

  $return = array();
  $return["details"] = $playlistjson;
  $users = array();
  $list = [];

  $next = "";
  do {
    $track = request((!empty($next) ? $next : "https://api.spotify.com/v1/users/".urlencode($user)."/playlists/".urlencode($playlist)."/tracks?fields=".urlencode("items(added_by.id),next")), "GET", "", ["Authorization: Bearer ".$token]);
    foreach ($track["items"] as $item) {
      if (isset($users[$item["added_by"]["id"]])) {
        $users[$item["added_by"]["id"]]++;
      } else {
        $users[$item["added_by"]["id"]] = 1;
      }
    }
    $next = $track["next"];
  } while (!empty($next));

  $return["people"] = array();
  foreach ($users as $id => $count) {
    $currentuser = request("https://api.spotify.com/v1/users/".urlencode($id)."?fields=display_name", "GET", "", ["Authorization: Bearer ".$token]);
    $return["people"][($currentuser !== false && isset($currentuser["display_name"]) && !empty($currentuser["display_name"]) ? $currentuser["display_name"] : $id)] = $count;
  }

  return $return;
}
