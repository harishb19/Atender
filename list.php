<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/attendance/admin/require1.php');

$token    = 'YOUR TOKEN';
$bot_name = $_POST['user_name'];
$icon     = ':alien:';
$message  = $_POST['text'];
$uid = $_POST['user_id'];
$team = $_POST['team_domain'];

if($team=='TEAM NAME'){
    $Helpid='ADMIN UID FROM SLACK';
    $url='YOUR WEBHOOK';

}


$help_attachments = array(
    0 =>array(
        'fallback'=> 'To view your latest 7 bunked days kindly type "/atender list"',
        'pretext'=> "To view your latest 7 bunked days kindly type '/atender list'\n To get your whole list Kindly contact your respective attendance coordinator",
        'title'=> 'Eg: /registerme list',
        'text'=> 'Contact <'.$Helpid.'> for further help.',
        'color'=> '#00796b'
    ),
);
$get_news = mysqli_query($connection, "SELECT COUNT(id) from attendance WHERE uid='$uid'");
$row = mysqli_fetch_assoc($get_news);
$total = $row['COUNT(id)'];
//$date = array();
$get_list = mysqli_query($connection, "SELECT * from attendance WHERE uid='$uid' ORDER by created_at DESC LIMIT 7 ");
$count = 0;
while($list = mysqli_fetch_assoc($get_list)){
    $data_list[]=$list['created_at'];
    $count = $count+1;
}

$dates = $data_list['0']."\n".$data_list['1']."\n".$data_list['2']."\n".$data_list['3']."\n".$data_list['4']."\n".$data_list['5']."\n".$data_list['6']."\n".$data_list['7'];
$sucess_attachments = array(
    0 =>array(
        'fallback'=> 'Here is your short list',
        'pretext'=> '<@'.$bot_name.'> here is the list of latest '. $count .' bunked days',
        'title'=> $dates.' Total days bunked '.$total.'.',
        'text'=> 'Did any mistake ? Contact <'.$Helpid.'> for further help.',
        'color'=> '#00796b'
    ),
);

$data = array(
    'username'    => $bot_name,
    'text'        => $message,
    'response_type' => 'ephemeral',
    'uid'  => $uid,
    'attachments'=>$sucess_attachments,
    'team' => $team
);

$data_string = json_encode($data);

$help_data=array(
    'response_type' => 'ephemeral',
    'username' => $bot_name,
    'attachments' => $help_attachments
);

$help_string = json_encode($help_data);


if(!$message){
    echo 'Type list or help';
//    print_r($count);
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
//    echo $help_string;
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

//        echo $data_string;
    }

    return $result;
}

?>
