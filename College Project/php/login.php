<?php
    require_once("configdb.php");
    session_start();

    //If seasion is alive
    if (isset($_SESSION['islogin'])) {
        sendError(3,"sucessful");
        exit();
    }
    
    function sendError($type,$msg){
            $error = ["type" => "", "err_name" => ""];
            $error['type'] = $type;
            $error['err_name'] = $msg;
            echo json_encode($error, true);
    }

    $userNameErr = $passErr = "";
    $data = file_get_contents("php://input");
    $formData = json_decode($data, true);

    $userName = mysqli_real_escape_string($conn, $formData['name']);
    $pass = mysqli_real_escape_string($conn, $formData['password']);

    //Validation of user name
    if (empty($userName)) {
        $userNameErr = "User name is required";
        sendError(1,$userNameErr);
    }

    //Validation of password
    if (empty($pass)) {
        $passErr = "Password is required";
        sendError(2,$passErr);
    }

    //Checking if user name already exists or not
    if (empty($userNameErr) && empty($passErr)) {
        $userQuery = "SELECT username, password from users WHERE username=?";
        $stmt = mysqli_prepare($conn, $userQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $userName);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) != 0) {
                    mysqli_stmt_bind_result($stmt, $username, $password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($pass, $password)) {
                            $_SESSION['username'] = strtolower($userName);
                            $_SESSION['isloggin'] = true;
                            sendError(3,"sucessfull");
                        } else {
                            $passErr = "Incorrect password";
                            sendError(2,$passErr);
                        }
                    }
                } else {
                    $userNameErr = "User name not found";
                    sendError(1,$userNameErr);
                }
            }
            mysqli_stmt_close($stmt);
        }
    }

?>