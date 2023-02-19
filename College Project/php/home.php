<?php
    session_start();
        if(isset($_SESSION['isloggin'])){
            
            //users 
            $sendData = ['user'=>$_SESSION['username'],
                        'isloggin'=>$_SESSION['isloggin']];
    
            echo json_encode($sendData);
        }else{
            $sendData = ['user'=>"",
            'isloggin'=>false];
        }
?>