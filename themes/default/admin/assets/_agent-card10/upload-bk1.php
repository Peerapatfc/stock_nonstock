<?php
// echo "555";die;
$upload_dir = 'assets/images/imgup/profile/' /* . date('YmdHis') . '.jpg' */; //implement this function yourself
$img = $_POST['hidden_data'];
// 	    echo json_encode(array($_POST['hidden_data'],000));die();
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$name= mktime() . ".png";
$file = $upload_dir .$name;
$success = file_put_contents($file, $data);
// print $success ? $file : 'Unable to save the file.';
require_once("config/db-connect.php"); 


$id = $_POST["id"];
$team = $_POST["team"];
$json = json_encode(array(
	'id'	=> $id,
   'team'	=> $team,
   'name'	=> $name,
));


// === setup image === //
$maxFileSize = 900;
$maxWidth = 3000;
$maxHeight = 2000;

// === get agent type === //
$conn = new connectMySqlDB();

//if(($success)){
    // print_r($_POST);
//     $imgData=$_POST['imgDataView2']!=''?$_POST['imgDataView2']:
//echo 
   $q_user = "update sma_users set agent_card='".$name."', team='".$team."' where id= ".$id;
    //die;
    $res_u = $conn->query($q_user);
    
//}
//echo $json;
print $success ? $name : 'Unable to save the file.';