<?php
require_once("configdb.php");
function sendStatus($type,$msg){
    $error = ["type" => "", "err_name" => ""];
    
    $error['type'] = $type;
    $error['err_name'] = $msg;
    echo json_encode($error, true);
}

$data = file_get_contents("php://input");
$formData = json_decode($data, true);

$name = mysqli_real_escape_string($conn, $formData['name']);
$userName = mysqli_real_escape_string($conn, $formData['user']);
$email = mysqli_real_escape_string($conn, $formData['email']);
$pass = mysqli_real_escape_string($conn, $formData['password']);
$connPass = mysqli_real_escape_string($conn, $formData['conPassword']);

//validate Name
if (empty($name)) {
    $nameErr = "Name is required";
    sendStatus(0,$nameErr);
} else {
    $name = mysqli_real_escape_string($conn, $formData['name']);
}


//Validate user name field
if (empty($userName)) {
    $userNameErr = "User name is required";    
    sendStatus(2,$userNameErr);

} else if (strlen($userName) < 6) {
    $userNameErr = "Name must be minimum six length";
    sendStatus(2,$userNameErr);
} else {

    //prepare query 
    $userQuery = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $userQuery);

    if ($stmt) {
        $param_user = mysqli_real_escape_string($conn, $formData['user']);
        mysqli_stmt_bind_param($stmt, 's', $param_user);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) != 0) {
                $userNameErr = "Username is already taken";
                sendStatus(2,$userNameErr);
            }
        }
    }
    mysqli_stmt_close($stmt);
}

//validate email
if (empty($email)) {
    $emailErr = "Email is required";
} else if (!preg_match("/^[a-zA-Z0-9.]{3,}@[a-zA-Z]{2,}[.][a-zA-Z]{2,}$/", $email)) {
    $emailErr = "Invalid email address";
    sendStatus(3,$emailErr);

} else {
    //prepare mysqli query 
    $emailQuery = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $emailQuery);

    if ($stmt) {
        $param_email = mysqli_real_escape_string($conn, $formData['email']);
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) != 0) {
                $emailErr="This email is already registered";
                sendStatus(3,$emailErr);
            }
        }
    }

    mysqli_stmt_close($stmt);
}

//validate password
if (empty($pass)) {
    sendStatus(0,$passErr);

} else if (strlen($pass) < 6) {
    $passErr = "Minimum length of password is 6";
    sendStatus(0,$passErr);
}

//validate confirm password
if (empty($connPass)) {
    $connErr = "Confirm password is required";
    sendStatus(0,$connErr);

} else if ($pass != $connPass) {
    $connErr = "Password does not match";
    sendStatus(0,$connErr);
}

//Insert data into database
if (
    empty($nameErr) && empty($userNameErr) && empty($passErr)
    && empty($emailErr) && empty($connErr)
) {
    $sql = "INSERT INTO `users`(`name`, `username`, `email`, `password`,`token`,`active`) 
            VALUES (?,?,?,?,?,?) ";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $name_param = $name;
        $userName_param = $userName;
        $email_param = $email;
        $token_param = bin2hex(random_bytes(15));
        $status_param = "inactive";

        $option = ['cost' => 5, 'salt' => "agorieingorjioer"];

        $pass_param = password_hash($pass, PASSWORD_BCRYPT, $option);

        mysqli_stmt_bind_param(
            $stmt,
            'ssssss',
            $name_param,
            $userName_param,
            $email_param,
            $pass_param,
            $token_param,
            $status_param
        );

        if (mysqli_stmt_execute($stmt)) {
            header("location:sucess.php");
        }
    }

    mysqli_stmt_close($stmt);
}

?>