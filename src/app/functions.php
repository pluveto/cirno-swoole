<?php

/**
 * Global functional extension
 * 全局功能扩展，用于扩充经常使用的函数
 * @author Pluveto
 */

/**
 *  ======= File system exts ======= 
 */

function rglob($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir . '/' . basename($pattern), $flags));
    }
    return $files;
}

/**
 * ======= Array exts ======= 
 */

/**
 * 如果存在 key 就返回对应 value, 否则返回默认值
 *
 * @param array $array
 * @param string $key
 * @param object $default
 * @return mixed
 */
function array_get_if_key_exists($array, $key, $default)
{
    return array_key_exists($key, $array) ? $array[$key] : $default;
}

/**
 * 检查数组是否包含所指定的全部 key
 *
 * @param string[] $keys
 * @param array $array
 * @return bool
 */
function array_keys_exists($keys, $array): bool
{
    foreach ($keys as $key) {
        if (!array_key_exists($key, $array)) {
            return false;
        }
    }
    return true;
}

/**
 * 检查不同数组，是否存在两个数组，满足：含有相同的某属性
 *
 * @param array $arraies
 * @param string $key
 * @return bool
 */
function array_val_conflict(array $arraies, string $key): bool
{
    for ($i = 0; $i < count($arraies); $i++) {
        for ($j = $i + 1; $j < count($arraies); $j++) {
            if ("" != trim($arraies[$i][$key]) && trim($arraies[$i][$key]) === trim($arraies[$j][$key])) {
                return true;
            }
        }
    }
    return false;
}


/**
 * 递归检查数组是否存在某个 key
 *
 * @param array $array
 * @param string $keySearch
 * @return boolean
 */
function array_key_exists_r(array $array, string $keySearch): bool
{
    foreach ($array as $key => $item) {
        if ($key == $keySearch) {
            return true;
        } elseif (is_array($item) && array_key_exists_r($item, $keySearch)) {
            return true;
        }
    }
    return false;
}

/**
 * 递归检查数组是否存在 key 包含某字符串
 *
 * @param array $array
 * @param string $search
 * @return boolean
 */
function array_key_contains_r(array $array, string $search): bool
{
    foreach ($array as $key => $item) {
        if (strpos($search, $key)) {
            return true;
        } elseif (is_array($item) && array_key_exists_r($item, $search)) {
            return true;
        }
    }
    return false;
}

function all_in_array($array, $standard){
    foreach ($array as $item) {
        if(!in_array($item, $standard)){
            return false;
        }
    }
    return true;
}


/**
 * 将字段组归类
 * 
 * 例如，有字段 {1, 2, 3, 4, 5} 归类数组为 {0,1,2} {4,5,6}，则归类结果为 {1,2} {4,5}
 *
 * @param array $fields
 * @param array $arrays
 * @return array
 */
function array_cluster($fields, $arrays){
    $retArrays = [];
    foreach ($arrays as $array) {
        $retArrays[] = array_intersect($fields, $array);
    }
    return $retArray;
}

/**
 * ======= Other exts ======= 
 */

/**
 * 将数组导出为字符串，并且进行格式化对齐
 *
 * @param array $expression
 * @return string
 */
function var_export_format(array $expression): string
{
    $export = var_export($expression, TRUE);
    $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
    $array = preg_split("/\r\n|\n|\r/", $export);
    $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [NULL, ']$1', ' => ['], $array);
    $export = join(PHP_EOL, array_filter(["["] + $array));
    return $export;
}

function uuid($data = null)
{
    if (null == $data) {
        $data = openssl_random_pseudo_bytes(16);
    }
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
