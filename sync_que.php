<?php
/**
 * Created by PhpStorm.
 * User: p2
 * Date: 6/5/14
 * Time: 5:18 PM
 */

require_once 'bootstrap.php';

set_time_limit(300);

function isEng($str){
    return strlen($str) == strlen(utf8_decode($str));
}

$em = Local::getEM();
$vem = Local::getVEM();

$qb = $em->getRepository('Main\Entity\Que\Que')->createQueryBuilder('a');
$qb->select('max(a.vn_id)');

$max_vn_id = $qb->getQuery()->getSingleScalarResult();
$max_vn_id = (int)$max_vn_id;

$vqb = $vem->getRepository('Main\Entity\View\QVisit')->createQueryBuilder('a');
$vqb
    ->where('a.vn_id>:vn_id')
    ->setParameter('vn_id', $max_vn_id);

$items = $vqb->getQuery()->getResult();

$rs = array();
$add = array();
foreach($items as $item){
    /** @var Main\Entity\View\QVisit $item */
    $ptType = $item->getPttype();
    $que = new Main\Entity\Que\Que();
    $que->setId($item->getVnId());
    $que->setVnId($item->getVnId());
    $que->setDate($item->getDate());
    $que->setTime(new DateTime($item->getTime().":00"));
    $que->setTimeDru(new DateTime($item->getTime().":00"));
    $que->setHnId($item->getHnId());
    $que->setPName(tis620_to_utf8($item->getPName()));
    $que->setPSurname(tis620_to_utf8($item->getPSurname()));
    $que->setDepId(tis620_to_utf8($item->getDepId()));
    $que->setDepName(tis620_to_utf8($item->getDepName()));
    $que->setDru(false);
    $que->setCas($item->getCas());
    $que->setPtType(trim($ptType));

    $em->persist($que);
    $em->flush();

    $imgPath = 'public/img/users/'.$item->getHnId().'.bmp';
    if(!file_exists($imgPath)){
        $pImg = $vem->getRepository('Main\Entity\View\PImage')->findOneBy(array('hn_id'=> $item->getHnId()));
        if(!is_null($pImg->getImage())){
            file_put_contents($imgPath, $pImg->getImage());
        }
        unset($pImg);
    }

    // download mp3 name,surname
    $lang = isEng($que->getPName())? 'en': 'th';

    $fcontent = file_get_contents("http://translate.google.com/translate_tts?tl={$lang}&ie=UTF-8&q=".urlencode($que->getPName()));
    $fp = fopen("public/sounds/firstname/".$que->getVnId().".mp3", 'w');
    fwrite($fp, $fcontent);
    fclose($fp);

    $lang = isEng($que->getPSurname())? 'en': 'th';
    $fcontent = file_get_contents("http://translate.google.com/translate_tts?tl={$lang}&ie=UTF-8&q=".urlencode($que->getPSurname()));
    $fp = fopen("public/sounds/lastname/".$que->getVnId().".mp3", 'w');
    fwrite($fp, $fcontent);
    fclose($fp);

    $rs[] = $que->toArray();
    $add[] = $que->toArray();
}


if(count($add) > 0){
    $wsClient = new \Main\Socket\Client\WsClient("127.0.0.1", 8083);

    $json = json_encode(array(
        'action'=> 'add',
        'param'=> $add
    ));
    echo $wsClient->sendData($json);
    unset($wsClient);
}