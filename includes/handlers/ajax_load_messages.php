<?php
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Message.php");

$limit = 7;

$message = new Message($con, $_REQUEST['user']);
echo $message->getConvosDropdown($_REQUEST, $limit);

?>
