<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/attendance/admin/require.php');

$token    = 'YOUR TOCKEN';
$bot_name = $_POST['user_name'];
$icon     = ':thumbsup:';
$message  = $_POST['text'];
$uid = $_POST['user_id'];
$team = $_POST['team_domain'];

if($team=='TEAM NAME'){
    $Helpid='UID OF ADMIN';
    $url='WEBHOOCK';
}

$help_attachments = array(
    0 =>array(
        'fallback'=> 'To register kindly write "/registerme followed by PRN and year-branch-div"',
        'pretext'=> 'To register kindly write "/registerme followed by [PRN] , [year-branch-div] Note : " , " between PRN and year-branch-div is compulsory "',
        'title'=> 'Eg: /registerme [115A1008] , [TE-CE-C]',
        'text'=> 'Contact <'.$Helpid.'> for further help.',
        'color'=> '#00796b'
    ),
);

$sucess_attachments = array(
    0 =>array(
        'fallback'=> 'Thanks for registering'.$icon,
        'pretext'=> '<@'.$bot_name.'> Successfully registered as',
        'title'=> $message.' for the '.$team.'  team',
        'text'=> 'Did any mistake ? Contact <'.$Helpid.'> for further help.',
        'color'=> '#00796b'
    ),
);

$data = array(
    'username'    => $bot_name,
    'text'        => $message,
    'response_type' => 'in_channel',
    'uid'  => $uid,
    'attachments'=>$sucess_attachments,
    'team' => $team
);

$data_string = json_encode($data);

$help_data=array(
    'username' => $bot_name,
    'response_type' => 'in_channel',
    'attachments' => $help_attachments
);

$help_string = json_encode($help_data);


if(!$message){
    echo 'PRN and year-branch-div must be specified in the given format ';
}
else{
    if ($message=='help'){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $help_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($help_string))
        );
        $result = curl_exec($ch);
//        echo $help_string;
    }

    else{
        $myArray = explode(',', $message);
        $prn=$myArray['0'];
        $class=$myArray['1'];
//
//        $get_news = mysqli_query($connection, "SELECT id from register WHERE prn='$prn'");
//        $row = mysqli_fetch_assoc($get_news);
//
//        if($row['id']!=0){
//            echo "Already registered";
//        }
//        else{
        $get_news = mysqli_query($connection, "SELECT id from register WHERE prn= '$prn' and team='$team'");
        $row = mysqli_fetch_assoc($get_news);

        if($row['id']!=0){
            echo "Already registered for" .$team;
        }
        else{

            if(!$prn || !$class){
                echo 'Kindly fill in the same manner [PRN] , [Year-Branch-Div]. Note: " , " is compulsory try using /registerme help';
            }
            else{
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data_string))
                );
                //Execute CURL
                $result = curl_exec($ch);
                $stmt = $connection->prepare("INSERT INTO register (uid,uname,team,prn,class,created_at) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $uid, $bot_name,$team ,$prn,$class ,$dat);
                // set parameters and execute
                $dt = new DateTime("now", new DateTimeZone('Asia/Kolkata'));
                $dat = $dt->format('Y-m-d H:i:s');
                $stmt->execute();
                $stmt->close();

//                 echo $data_string;
            }
        }

    }

    return $result;
}















?>
