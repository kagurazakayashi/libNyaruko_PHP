<?php
class NyaData {
    /**
     * @description: 從字典中抽出指定的 Key ，並生成一個新的字典。
     * @param Array add 原始字典陣列
     * @param String keys... 要取出的所有 Key
     * @return Array 新建立的字典
     */
    function dicExtract(array $dicArr, string ...$keys) {
        $newDic = [];
        foreach ($keys as $key) {
            if (isset($dicArr[$key])) {
                $newDic[$key] = $dicArr[$key];
            }
        }
        return $newDic;
    }

    /**
     * @description: 檢查陣列中是否包括指定的一組 key
     * @param Array nowarray 需要被測試的陣列
     * @param Array<String> keys 需要檢查是否有哪些 key 的陣列
     * @param Bool getcount 是否返回不匹配的數量，否則返回具體不匹配的 key 陣列
     * @return Array<String> 不匹配的 key 陣列
     * @return Int 不匹配的數量
     */
    function keyinarray(array $nowarray, array $keys, bool $getcount = true) {
        $novalkey = array();
        foreach ($keys as $nowkey) {
            if (!isset($nowarray[$nowkey])) {
                array_push($novalkey, $nowkey);
            }
        }
        if ($getcount) return count($novalkey);
        return $novalkey;
    }

    /**
     * @description: 檢查一維陣列中是否都為某個值
     * @param Object search 物件
     * @param Array array 要檢查的陣列
     * @param Bool type 使用全等進行判斷
     * @return Bool 是否都為某個值
     */
    function allinarray($search, array $array, bool $type = true): bool {
        foreach ($array as $value) {
            if (($type && $value !== $search) || (!$type && $value != $search)) return false;
        }
        return true;
    }

    /**
     * @description: 為陣列中的所有鍵增加一個字首
     * @param Array arr 要處理的陣列
     * @param String prefix 要新增的字首
     * @return Array 修改後的陣列
     */
    function arraykeyprefix(array $arr = null, string $prefix = ""): array {
        if (!isset($arr) || count($arr) == 0) return $arr;
        $newarr = [];
        foreach ($arr as $key => $value) {
            $newkey = $prefix . $key;
            $newarr[$newkey] = $value;
        }
        return $newarr;
    }

    /**
     * @description: 將
     * {"key1":["val1","val2"],"key2":["val1","val2"]...}
     * 轉換為
     * [{"key1":"val1"},{"key2":"val2"}...]
     * @param Array 需要整理的陣列(根為關聯陣列)
     * @return Array 轉換後的陣列(根為索引陣列)
     */
    function dicvals2arrsdic(array $dic): array {
        $newarr = [];
        if (!$dic || count($dic) == 0) return $newarr;
        $keys = array_keys($dic);
        $datacount = count($dic[$keys[0]]);
        for ($i = 0; $i < $datacount; $i++) {
            $nowdata = [];
            foreach ($keys as $nowkey) {
                $nowdata[$nowkey] = $dic[$nowkey][$i];
            }
            array_push($newarr, $nowdata);
        }
        return $newarr;
    }

    /**
     * @description: 陣列是否全都是 null
     * @param Array nowarray 需要被測試的陣列
     * @return Bool 是否全都是 null
     */
    function allnull(array $nowarray):bool {
        if (!$nowarray || count($nowarray) == 0) return false;
        foreach ($nowarray as $key => $value) {
            if ($value != null) return false;
        }
        return true;
    }

    /**
     * @method 多維陣列轉一維陣列
     * @param Array array 多維陣列
     * @return Array array 一維陣列
     */
    function multiAarray2array(array $array):array {
        static $result_array = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->multiAarray2array($value);
            } else
                $result_array[$key] = $value;
        }
        return $result_array;
    }


}
