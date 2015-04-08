<?php

  require_once 'st-api-client.php';
  header('Content-Type: text/html; charset=UTF-8');

  echo 'Categories:'."\n\n";
  $categories = STAPIClient::categorieslist()
    ->order('asc')
    ->offset(1)
    ->limit(10)
    ->exec($error);
  if (!$error) {
    foreach ($categories as $category) {
      print_r($category);
    }
  }
  else {
    echo 'Error: '.$error;
    return false;
  }

  echo "\n\n".'Events:'."\n\n";
  $events = STAPIClient::eventslist()
    ->order('desc')
    ->offset(0)
    ->limit(3)
    ->date(date('Y-m-d'))
    ->exec($error);
  if (!$error) {
    foreach ($events as $event) {
      print_r($event);
    }
  }
  else {
    echo 'Error: '.$error;
    return false;
  }

  if (count($events)) {
    echo "\n\n".'Full event:'."\n\n";
    $event = STAPIClient::event()
      ->id($events[0]['id'])
      ->exec($error);
    if (!$error) {
      print_r($event);
    }
    else {
      echo 'Error: '.$error;
      return false;
    }
  }

  if (count($categories)) {
    echo "\n\n".'More events:'."\n\n";
    $events = STAPIClient::eventslist()
      ->order('asc')
      ->offset(1)
      ->limit(3)
      ->periodStart('2015-01-01')
      ->periodEnd('2015-03-02')
      ->category($categories[2]['id'])
      ->exec($error);
    if (!$error) {
      foreach ($events as $event) {
        print_r($event);
      }
    }
    else {
      echo 'Error: '.$error;
      return false;
    }
  }


?>
