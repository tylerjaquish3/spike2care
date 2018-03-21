<?php 
session_start();
session_destroy();

include('functions.php');

header("location: ".URL);