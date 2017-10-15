<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/attendance/admin/require.php');

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
        'fallback'=> 'To mark your attendance kindly write "/markmein followed by the time and reason" - Eg: /markein 10:00-2:00 publicity at XYZ college',
        'pretext'=> 'To mark your attendance kindly write "/markmein followed by the [time] , [reason]. Note: " , " between [time] and [reason] is compulsory"',
        'title'=> 'Eg: /markmein [10:00-2:00] , [publicity at XYZ college]',
        'text'=> 'Contact <'.$Helpid.'> for further help.',
        'color'=> '#00796b'
    ),
);


$sucess_attachments = array(
    0 =>array(
        'fallback'=> 'attendance marked for the day',
        'pretext'=> '<@'.$bot_name.'> Attendance was marked as',
        'title'=> $message,
        'text'=> 'Did any mistake ? Contact <'.$Helpid.'> for further help.',
        'color'=> '#00796b'
    ),
);

$data = array(
    'username'    => $bot_name,
    'text'        => $message,
    'icon_emoji'  => $icon,
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

    echo 'reason of bunking must be specified';

//   if attendance system is closed
//    echo "Atrende Has been closed for this sem \nMeet you in next sem :)";

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

        $get_date = mysqli_query($connection, "SELECT created_at from attendance WHERE uid='$uid' AND created_at<'$dat1'");
        $row1 = mysqli_fetch_assoc($get_date);
        $get_id = mysqli_query($connection, "SELECT * from attendance WHERE uid='$uid' ");
        $rowid = mysqli_fetch_assoc($get_id);
        if(!$row1['created_at'] && $rowid['id']){
            echo "Attendance already marked for the day \n Something wrong ? \n contact Harish";
        }
        else {
            $get_news = mysqli_query($connection, "SELECT id from register WHERE uid='$uid'");
            $row = mysqli_fetch_assoc($get_news);
            if ($row['id'] != 0) {
                $myArray = explode(',', $message);
                $timearray = $myArray['0'];
                $reasonarray = $myArray['1'];
                if (!$reasonarray || !$timearray) {
                    echo 'Kindly fill in the same manner [time] , [reason]  Note: " , " is compulsory. try using /markmein help';
                }
                else {
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
                    echo $result;

                    $stmt = $connection->prepare("INSERT INTO attendance (uid,uname,team,reason,created_at) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $uid, $bot_name,$team,$message ,$dat);

                    // set parameters and execute
                    $dt = new DateTime("now", new DateTimeZone('Asia/Kolkata'));
                    $dat = $dt->format('Y/m/d H:i:s');
                    $stmt->execute();
                    $stmt->close();
                    //        echo $data_string;

                }
            }
            else{
                echo "Please register first \n to know how to get registered type /regesterme help \n If already registered please check your team \n Need more help ? \n contact Harish";
            }
        }
    }

    return $result;
}

//else{
//    echo "Atrende Has been closed for this sem \nMeet you next sem :)";
//}

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
        $get_news = mysqli_query($connection, "SELECT id from register WHERE uid='$uid'");
        $row = mysqli_fetch_assoc($get_news);
        if($row['id']!=0){
            $myArray = explode(',', $message);
            $timearray=$myArray['0'];
            $reasonarray=$myArray['1'];
            if(!$reasonarray || !$timearray){
                echo 'Kindly fill in the same manner [time] , [reason]  Note: " , " is compulsory. try using /markmein help';
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

                $stmt = $connection->prepare("INSERT INTO attendance (uid,uname,team,reason,created_at) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $uid, $bot_name,$team,$message ,$dat);

                // set parameters and execute
                $dt = new DateTime("now", new DateTimeZone('Asia/Kolkata'));
                $dat = $dt->format('Y/m/d H:i:s');
                $stmt->execute();
                $stmt->close();
//        echo $data_string;

            }
        }
        else{
            echo "Please register first \n to know how to get registered type /regesterme help \n If already registered please check your team \n Need more help ? \n contact Harish";
        }
    }

    return $result;
}

















?>
