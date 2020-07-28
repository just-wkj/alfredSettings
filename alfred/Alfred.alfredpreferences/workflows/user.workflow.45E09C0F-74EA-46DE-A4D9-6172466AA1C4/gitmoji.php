<?php
require_once('workflows.php');
$w = new Workflows();
$query = urldecode(strtolower(trim($query)));

////<!-- 一周更新一次 -->
if (filemtime("data.json") <= (time() - 86400 * 7)) {
    $dataUrl = 'https://raw.githubusercontent.com/carloscuesta/gitmoji/master/src/data/gitmojis.json';
    $gitmojis = $w->request($dataUrl);
    if (isset(json_decode($gitmojis)->gitmojis)) {
        file_put_contents("data.json", $gitmojis);
    }
}

//<!-- 将 gitmoji 的数据设置成 Alfred 可读的数据结构  -->
function setResult ($gitmojis) {
    global $w;
    foreach ($gitmojis as $key => $value) {
        $id = $value->name;
        $arg = $value->code;
        $emoji = $value->emoji;
        $title = $emoji." ".$value->description;
        $subTitle = "Copy ".$arg." to clipboard";
        $w->result($id, $arg, $title, $subTitle, ' ');
    }
}

//<!-- 通过搜索词过滤出结果 -->
function filter($var) {
    global $query;
    $description = strtolower($var->description);
    $name = strtolower($var->name);
    return strpos($description,$query) !== false || strpos($name,$query) !== false;
}
$gitmojis = json_decode(file_get_contents('data.json'))->gitmojis;
$data = $gitmojis;

//<!-- 没有关键词就显示所有 gitmoji -->
if (strlen($query) != 0) {
    $data = array_filter($gitmojis, "filter");
}

//<!-- 得到的搜索，设置到缓冲区 -->
setResult($data);

//<!-- 将结果输出成 Alfred 可读的 xml -->
echo $w->toxml();