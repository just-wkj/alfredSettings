<?php
/**
 * @author: justwkj
 * @date: 2020/7/22 13:17
 * @email: justwkj@gmail.com
 * @desc:
 */


function outputJson($data) {
    $itemArr = [];
    foreach ($data as $datum) {
        $tmp = [
            'title' => $datum['title'],
            'arg'   => isset($datum['arg']) ? $datum['arg'] : '',
        ];
        if (isset($datum['quicklookurl'])) {
            $tmp['quicklookurl'] = $datum['quicklookurl'];
        }
        $itemArr[] = $tmp;
    }
    $data = ['items' => $itemArr];
    echo json_encode($data);
    die;
}


$query = empty($argv[1]) ? '' : $argv[1];
//chrome插件无法打开,选择一个在线地址
//$prettyJson = 'chrome-extension://pkgccpejnmalmdinmhkkfafefagiiiad/dynamic/index.html?tool=json-format';
$prettyJson = 'http://json.la/';
$input = shell_exec('pbpaste');
if (!is_array(json_decode($input, true))) {
    outputJson([
        [
            "title" => 'JSON格式有误!!!',
            "arg"   => '',
        ],
        [
            "title"        => 'JSON美化工具, command+enter 跳转',
            "arg"          => $prettyJson,
            'quicklookurl' => $prettyJson,
        ],
    ]);
}
$input = json_encode(json_decode($input, true), 256);

$miniJson = $input;

$data = [];
if (in_array($query, ['yasuo', 'm', 'y'])) {
    $data = [
        [
            'title' => '压缩JSON',
            'arg'   => $miniJson,
        ],
    ];
} else if (in_array($query, ['meihua', 'g', 'p'])) {
    $data = [

        [
            'title'        => 'JSON美化工具, command+enter 跳转',
            'arg'          => $prettyJson,
            'quicklookurl' => $prettyJson,
        ],
    ];
} else {
    $data = [
        [
            'title' => '压缩JSON',
            'arg'   => $miniJson,
        ],
        [
            'title'        => 'JSON美化工具, command+enter 跳转',
            'arg'          => $prettyJson,
            'quicklookurl' => $prettyJson,
        ],
    ];
}

outputJson($data);