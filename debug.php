<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    $data = $_REQUEST["data"];
    $fname = $_REQUEST["fname"];
    $handle = fopen("debug/".$fname.".txt", "w+");
    fwrite($handle, $data);
?>
