<?php
    session_start();
    if(isset($_SESSION['isloggin'])){
        session_destroy();
    }
?>