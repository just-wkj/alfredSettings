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
$input = shell_exec('pbpaste');

$array = array_filter(preg_split('/\s+/', $input));

$joinSeparated = $query == '"' ? '"' : "'";

$str = '';
foreach ($array as $item) {
    $str .= sprintf('%s%s%s,' . PHP_EOL, $joinSeparated, $item, $joinSeparated);
}
outputJson([[
    "title" => '格式化完毕:' . $str,
    "arg"   => $str,
]]);

