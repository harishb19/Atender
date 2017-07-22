<?php

$connection = mysqli_connect('localhost', 'username', 'password', 'dbname');
if(!$connection) {
    echo "Some Error.Please Try Again.";
}

$token    = 'iRGeaLZjmBrgn0pXWW55boc6';
$bot_name = $_POST['user_name'];
$icon     = ':alien:';
$message  = $_POST['text'];
$uid = $_POST['user_id'];
$team = $_POST['team_domain'];
$get_token = $_POST['token'];

if($get_token==$token){

    $attachments = array (
        0 =>
            array (
                'text' => 'Choose a game to play',
                'fallback' => 'You are unable to choose a game',
                'callback_id' => 'wopr_game',
                'color' => '#3AA3E3',
                'attachment_type' => 'default',
                'actions' =>
                    array (
                        0 =>
                            array (
                                'name' => 'game',
                                'text' => $bot_name,
                                'type' => 'button',
                                'value' => 'chess',
                            ),
                        1 =>
                            array (
                                'name' => 'game',
                                'text' => $message,
                                'type' => 'button',
                                'value' => 'maze',
                            ),
                        2 =>
                            array (
                                'name' => 'game',
                                'text' => 'ok',
                                'style' => 'danger',
                                'type' => 'button',
                                'value' => 'war',
                                'confirm' =>
                                    array (
                                        'title' => 'Are you sure?',
                                        'text' => 'Wouldn\'t you prefer a good game of chess?',
                                        'ok_text' => 'Yes',
                                        'dismiss_text' => 'No',
                                    ),
                            ),
                    ),
            ),
    );

    $help_attachments = array(
        0 =>array(
            'fallback'=> 'To mark your attendance kindly write "/markmein followed by the time and reason" - Eg: /markein 10:00-2:00 publicity at XYZ college',
            'pretext'=> 'To mark your attendance kindly write "/markmein followed by the time and reason"',
            'title'=> 'Eg: /markmein 10:00-2:00 publicity at XYZ college',
            'text'=> 'Contact <@U4EQTFHKK|harishb.py> for further help.',
            'color'=> '#00796b'
        ),
    );

    $sucess_attachments = array(
        0 =>array(
            'fallback'=> 'attendance marked for the day',
            'pretext'=> '<@'.$bot_name.'> Attendance was marked as',
            'title'=> $message,
            'text'=> 'Did any mistake ? Contact <@U4EQTFHKK|harishb.py> for further help.',
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
    }
    else{
        if ($message=='help'){
            $ch = curl_init('https://hooks.slack.com/services/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');
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
                $ch = curl_init('https://hooks.slack.com/services/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');
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
            else{
                echo 'Please register first \n to know how to get registered type /regesterme help ';
            }
        }

        return $result;
    }
}

else{
    echo 'Something went wrong (Error T404)! Kindly contact Harish ';
}












?>
