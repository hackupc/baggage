<?php
    include "config.php";

    if((isset($_POST['reg_row']))&&(isset($_POST['reg_col']))){
      $def_row = $_POST['reg_row'];
      $def_col = $_POST['reg_col'];
    }
    else{
      global $db_server;
      global $db_user;
      global $db_pass;
      global $db_name;
      $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
      $conn->set_charset("utf8");
      if($conn->connect_error){
        die("Connection failed: ".$conn->connect_error);
      }
      if(htmlspecialchars($_POST['reg_spe'])!='Special'){
        $query = "
          SELECT pos.row, pos.col
          FROM hupc_positions pos
          WHERE (pos.deleted IS NULL) AND (pos.row <> '@')
          ORDER BY pos.row, pos.col;";
      }
      else{
        $query = "
          SELECT pos.row, pos.col
          FROM hupc_positions pos
          WHERE (pos.deleted IS NULL) AND (pos.row = '@')
          ORDER BY pos.row, pos.col;";
      }
      $places = array();
      if($places2 = $conn->query($query)){
        while($row = $places2->fetch_assoc()){
          $places[] = $row;
        }
        $places2->free();
      }
      $conn->close();

      if(htmlspecialchars($_POST['reg_spe'])!='Special'){
        global $rows;
        global $cols;
        $rows_i = 0;
        $cols_i = 0;
        $places_org = array();
        while($rows_i<$rows){
          while($cols_i<$cols){
            $places_org[] = array("row"=>chr($rows_i+65), "col"=>$cols_i);
            $cols_i++;
          }
          $cols_i = 0;
          $rows_i++;
        }
        $founded=false;
        $def_row=NULL;
        $def_col=NULL;
        $size=sizeof($places);
        $size_org=sizeof($places_org);
        $pos=0;
        $pos_org=0;
        while((!$founded)&&($pos_org<$size_org)){
          if(($pos<$size)&&($places_org[$pos_org]["row"]==$places[$pos]["row"])&&($places_org[$pos_org]["col"]==$places[$pos]["col"])){
            $pos++;
          }
          else{
            $founded = true;
            $def_row = $places_org[$pos_org]["row"];
            $def_col = $places_org[$pos_org]["col"];
          }
          $pos_org++;
        }
      }
      else{
        $founded=false;
        $size=sizeof($places);
        $pos=0;
        $pos2=0;
        $def_row = '@';
        while((!$founded)&&($pos<$size)){
          if($pos!=$places[$pos]["col"]){
            $founded = true;
            $def_col = $pos;
          }
          if($pos==$places[$pos2]["col"]){
            $pos2++;
          }
          $pos++;
        }
        if(!$founded){
          $def_col = $pos;
        }
      }
    }

    $reg_row = $def_row;
    $reg_col = $def_col;
    $reg_id = htmlspecialchars($_POST['reg_id']);
    $reg_name = htmlspecialchars($_POST['reg_name']);
    $reg_surname = htmlspecialchars($_POST['reg_surname']);
    $reg_desc = htmlspecialchars($_POST['reg_desc']);
    global $db_server;
    global $db_user;
    global $db_pass;
    global $db_name;
    $conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
    $conn->set_charset("utf8");
    if($conn->connect_error){
      die("Connection failed: ".$conn->connect_error);
    }
    $query = "
    INSERT INTO hupc_positions
    VALUES('".$reg_row."', '".$reg_col."', '".$reg_id."', '".$reg_name."', '".$reg_surname."', CURRENT_TIMESTAMP, NULL, '".$reg_desc."');";
    $result = mysqli_query($conn, $query);
    header('Location: ../../?rem_row='.$reg_row.'&rem_col='.$reg_col);
?>
