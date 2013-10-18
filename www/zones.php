<?php

class Zones {

  function __construct($path) {
    $this->path = $path;
  }

  function list_zones() {
    if (!is_dir($this->path)) {
      return array();
    }
    $zones = scandir($this->path);
    sort($zones);
    array_shift($zones);
    array_shift($zones);
    return $zones;
  }

  private function get_zone_file($name) {
    return $this->path . "/" . $name;
  }

  function get_zone($name) {
    $file = $this->get_zone_file($name);
    if (!file_exists($file)) {
      return array();
    }
    $content = file_get_contents($file);
    $content = explode("\n", $content);
    $content = array_filter($content, function($x) {
      return $x != "";
    });
    $map = array();
    foreach($content as $x) {
      $s = explode(" ", $x);
      $ip = array_shift($s);
      $result = array();
      foreach($s as $ss) {
        if ($ss != "") {
          array_push($result, $ss);
        }
      }
      $map[$ip] = $result;
    }
    return $map;
  }

  function delete_zone($name) {
    $file = $this->get_zone_file($name);
    if (!file_exists($file)) {
      return false;
    }
    return unlink($file);
  }

  private function dump_zone($file, $map) {
    $s = "\n";
    foreach(array_keys($map) as $k) {
      $s .= $k;
      foreach($map[$k] as $kk) {
        $s .= " ".$kk;
      }
      $s .= "\n";
    }
    return file_put_contents($file, $s);
  }

  function add_record($name, $ip, $alias) {
    $file = $this->get_zone_file($name);
    $z = $this->get_zone($name);
    if (!isset($z[$ip])) {
      $z[$ip] = array();
    }
    if (!in_array($alias, $z[$ip])) {
      array_push($z[$ip], $alias);
    }
    return $this->dump_zone($file, $z);
  }

  function delete_record($name, $ip) {
    $file = $this->get_zone_file($name);
    $z = $this->get_zone($name);
    unset($z[$ip]);
    return $this->dump_zone($file, $z);
  }

}

