<?php
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Notification.php");

$limit = 7; //Number of notifications to load

$notification = new Notification($con, $_REQUEST['user']);
echo $notification->getNotification($_REQUEST, $limit);

?>
