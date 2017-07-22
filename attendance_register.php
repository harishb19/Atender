<?php

$connection = mysqli_connect('localhost', 'username', 'password', 'dbname');
if(!$connection) {
    echo "Some Error.Please Try Again.";
}
$token    = 'hCKEyTfvbr03Vuet3SKNXAca';
$bot_name = $_POST['user_name'];
$icon     = ':thumbsup:';
$message  = $_POST['text'];
$uid = $_POST['user_id'];
$team = $_POST['team_domain'];
$get_token = $_POST['token'];

if($get_token==$token){
    $help_attachments = array(
        0 =>array(
            'fallback'=> 'To register kindly write "/registerme followed by PRN and year-branch-div"',
            'pretext'=> 'To register kindly write "/registerme followed by PRN and year-branch-div"',
            'title'=> 'Eg: /registermw 115A1008 TE-CE-C',
            'text'=> 'Contact <@U4EQTFHKK|harishb.py> for further help.',
            'color'=> '#00796b'
        ),
    );

    $sucess_attachments = array(
        0 =>array(
            'fallback'=> 'Thanks for registering'.$icon,
            'pretext'=> '<@'.$bot_name.'> Successfully registered as',
            'title'=> $message.'for the'.$team.'team',
            'text'=> 'Did any mistake ? Contact <@U4EQTFHKK|harishb.py> for further help.',
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
                echo "Already registered";
            }
            else{
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
                $stmt = $connection->prepare("INSERT INTO register (uid,uname,team,details,created_at) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $uid, $bot_name,$team ,$message ,$dat);

                // set parameters and execute
                $dt = new DateTime("now", new DateTimeZone('Asia/Kolkata'));
                $dat = $dt->format('Y-m-d H:i:s');
                $stmt->execute();
                $stmt->close();
//                 echo $data_string;
            }

        }

        return $result;
    }

}

else{
    echo 'Something went wrong (Error T404)! Kindly contact Harish ';
}














?>
