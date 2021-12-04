<?php

    foreach (glob($_SERVER['DOCUMENT_ROOT'] . '/satellites/building/php/model/*.php') AS $models) { require($models); }
    foreach (glob($_SERVER['DOCUMENT_ROOT'] . '/satellites/building/php/view/*.php') AS $views) { require($views); }
    foreach (glob($_SERVER['DOCUMENT_ROOT'] . '/satellites/building/php/controller/*.php') AS $controllers) { require($controllers); }

?>