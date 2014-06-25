<?php
/**
 * Created by PhpStorm.
 * User: p2
 * Date: 6/12/14
 * Time: 10:55 PM
 */

$vn_id = $_POST['vn_id'];
$suffix = $_POST['suffix_path'];
$room_path = $_POST['room_path'];
$em = Local::getEM();

$call = new \Main\Entity\Que\CallDru();
$call->setVnId($vn_id);
$call->setSuffixPath($suffix);
$call->setRoomPath($room_path);

$em->persist($call);
$em->flush();

echo json_encode(array('success'=> true));