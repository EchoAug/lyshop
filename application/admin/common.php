<?php
//除法保留两位小数
function divideSaveDouble($son, $mon)
{
    $dele = $mon - $son;
    $res = $dele / $mon;
    return sprintf("%.2f", $res);
}

function my_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
{
    if (is_array($arrays)) {
        foreach ($arrays as $array) {
            if (is_array($array)) {
                $key_arrays[] = $array[$sort_key];
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
    array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
    return $arrays;
}