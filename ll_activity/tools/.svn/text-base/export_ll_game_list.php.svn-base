<?php
define("RUN_MODE", 'production');
require_once dirname(dirname(__FILE__))  . "/include/config.inc.php";
require_once dirname(dirname(__FILE__)) . "/utils/DB.php";
$pl = "android";

function getAllGamesInfo() {
    global $pl;
    $db = new Db();
    $db->use_db("xxbackend_production_read");
    $sql = "select a.ll_package as Package, b.Name,b.Version,b.UpdateTime,b.Description, b.UpdateDescription, b.Downloads, b.Images, a.ll_fileid as FileID, b.photos, b.shortDesc from {$pl}_66_game_list a left join {$pl}_xx_game_list b on a.game_id = b.ID where a.id > 2000";
    var_dump($sql);
    $data = $db->query($sql);
    foreach ($data as $index => $v) {
        $data[$index]['Images_Pic'] = getPictures($db,$v['Images']);
        $data[$index]['photos_Pic'] = getPictures($db,$v['photos']);
        $data[$index]['FileInfo'] = getAppFile($db,$v['FileID']);
        $data[$index]['Thumbnail'] = getPictures($db,$v['Thumbnail']);
    }
    return $data;
}
function getAppFile($db,$id) {
    global $pl;
    $sql = "select * from {$pl}_xx_app_file_list where ID = {$id}";
    return $db->query($sql)[0];
}
function getPictures($db, $id) {
    global $pl;
    $sql = "select * from {$pl}_xx_picture_file_list where ID in ({$id})";
    return $db->query($sql);
}
function insertPictures($db, $infos) {
    $result = array();
    foreach ($infos as $k=>$v) {
        $url = $v['URL'];
        $ft = $v['FileType'];
        $source = $v['Source'];
        $sql = "insert into ll_picture_file_list (URL,FileType,Source) values ('{$url}','{$ft}','{$source}')"; 
        var_dump($sql);
        $res = $db->query($sql);
        var_dump($res);
        $result[]=$db->next_id();
    }
    return $result;
}

function insertFiles($db, $files) {
    $fn = $files['Filename'];
    $ft = $files['FileType'];
    $url = $files['URL'];
    $s = $files['Size'];
    $cs = $files['Checksum'];
    $sql = "insert ll_app_file_list (Filename,URL, Filetype,Size,Checksum) values ('{$fn}','{$url}','{$ft}','{$s}','{$cs}')";
    $res = $db->query($sql);
    return $db->next_id();
}

function insertOneGameInfo($db,$v) {
    global $pl;
    $pkg = $v['Package'];
    $name = $v['Name'];
    $ver = $v['Version'];
    $ut = $v['UpdateTime'];
    $dp = $v['Description'];
    $udp = $v['UpdateDescription'];
    $downloads = $v['Downloads'];
    $fid = insertFiles($db,$v['FileInfo']);
    $photos = implode(",",insertPictures($db, $v['photos_Pic']));
    $im = implode(",",insertPictures($db, $v['Images_Pic']));
    $sd = $v['shortDesc'];
	$icon = insertPictures($db, $v['Thumbnail'])[0];
    if ($pl == 'ios') {
        $plaform = 101;
    }else {
        $platform = 102;
    }
    $sql = "insert into ll_game_list (Package,Name,Version,UpdateTime,Description,UpdateDescription,Downloads, Images,FileID,Platform,Photos,shortDesc,Icon) values (";
	$sql = $sql . "'{$pkg}','{$name}','{$ver}','{$ut}','{$dp}','{$udp}','{$downloads}','{$im}','{$fid}','{$platform}','{$photos}','{$sd}',{$icon})";
    var_dump($db->query($sql));
}

function main() {
    $games = getAllGamesInfo();
    $db = new Db();
    $db->use_db("llbackend_write");
    foreach ($games as $k=>$v) {
        insertOneGameInfo($db,$v);
    }
}
main();
