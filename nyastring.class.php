<?php
class NyaString {
    /**
     * @description: 只保留字串中所有的非字母和數字
     * @param String str 源字串
     * @return String 經過過濾的字元
     */
    function retainletternumber(string $str): string {
        return preg_replace('/[\W]/', '', $str);
    }

    /**
     * @description: 只保留字串中所有的數字
     * @param String str 源字串
     * @return String 經過過濾的字元
     */
    function retainnumber(string $str): string {
        return preg_replace('/[^\d]/', '', $str);
    }

    /**
     * @description: 檢查是否為電子郵件地址
     * @param String str 源字串
     * @return Bool 是否正確
     */
    function isEmail(string $str): bool {
        if (strlen($str) > 64) return false;
        $checkmail = "/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";
        if (isset($str) && $str != "") {
            if (preg_match($checkmail, $str)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @description: 檢查是否為 IPv4 地址
     * @param String ip IP地址源字串
     * @return Bool 是否正確
     */
    function isIPv4(string $ip): bool {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }
    /**
     * @description: 檢查是否為 IPv6 地址
     * @param String ip IP地址源字串
     * @return Bool 是否正確
     */
    function isIPv6(string $ip): bool {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    /**
     * @description: 檢查是否為私有 IP 地址
     * @param String ip IP地址源字串
     * @return Bool 是否正確
     */
    function isPubilcIP(string $ip): bool {
        if (filter_var($ip, FILTER_FLAG_NO_PRIV_RANGE) || filter_var($ip, FILTER_FLAG_NO_RES_RANGE)) return false;
        return true;
    }

    /**
     * @description: 判斷IP地址型別，輸出為存入資料庫的程式碼
     * @param String ip IP地址源字串
     * @return Int 0:未知,40:v4,60:v6,+1:公共地址
     */
    function iptype(string $ip): int {
        $iptype = "other";
        if ($this->isIPv4($ip)) $iptype = "ipv4";
        else if ($this->isIPv6($ip)) $iptype = "ipv6";
        if ($this->isPubilcIP($ip)) $iptype = $iptype . "_local";
        return $iptype;
    }

    /**
     * @description: 檢查是否為整數數字字串
     * @param String str 源字串
     * @return Bool 是否正確
     */
    function isInt(string $str): bool {
        $v = is_numeric($str) ? true : false; //判断是否为数字或数字字符串
        if ($v) {
            if (strpos($str, ".")) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * @description: 判斷字元型別
     * @param String str 源字串
     * @return Int 0:其他字元 1:數字 2:小寫字母 3:大寫字母
     */
    function chartype(string $str): int {
        if (preg_match("/^\d*$/", $str)) {
            return 1;
        } else if (preg_match('/^[a-z]+$/', $str)) {
            return 2;
        } else if (preg_match('/^[A-Z]+$/', $str)) {
            return 3;
        } else {
            return 0;
        }
    }

    /**
     * @description: 檢查字串是否僅包含字母和數字並滿足指定長度條件
     * @param String str 源字串
     * @param Int minlen 至少需要多長（可選,預設-1不限制）
     * @param Int maxlen 至多需要多長（可選,預設-1不限制）
     * @return Bool 是否正確
     */
    function isNumberOrEnglishChar(string $str, int $minlen = -1, int $maxlen = -1): bool {
        if (preg_match("/^[A-Za-z0-9]+$/i", $str) == 0) return false;
        $len = strlen($str);
        if ($minlen != -1 && $len < $minlen) return false;
        if ($maxlen != -1 && $len > $maxlen) return false;
        return true;
    }

    /**
     * @description: 檢查是否為中國手機電話號碼格式
     * @param String str 源字串
     * @return Bool 是否正確
     */
    function isPhoneNumCN(string $str): bool {
        $checktel = "/^1[345789]\d{9}$/";
        if (isset($str) && $str != "") {
            if (preg_match($checktel, $str)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @description: 分離手機號碼中的國別碼（如果沒有國別碼預設+86），並去除所有符號
     * @param String telstr 電話號碼字串
     * @return Array[String,String] 國別碼字串和電話號碼字串
     */
    function telarea(string $telstr): array {
        $area = "86";
        $tel = "";
        if (!preg_match("/^[\+a-z\d\-( )]*$/i", $telstr)) {
            return ["", ""];
        }
        if (substr($telstr, 0, 1) == '+' || substr($telstr, 0, 2) == '00') {
            $telarr = explode(" ", $telstr);
            $area = array_shift($telarr);
            $tel = implode("", $telarr);
        } else {
            $tel = $telstr;
        }
        return [$this->findNum($area), $this->findNum($tel)];
    }

    /**
     * @description: 取出字串中的所有數字
     * @param String str 原字串
     * @return String 純數字字串
     */
    function findNum(string $str = ''): string {
        $str = trim($str);
        if (empty($str)) return '';
        $result = '';
        for ($i = 0; $i < strlen($str); $i++) {
            if (is_numeric($str[$i])) $result .= $str[$i];
        }
        return $result;
    }

    /**
     * @description: 判斷是否為Base64字串
     * @param String b64string Base64字串
     * @param Bool urlmode 是否為轉換符號後的Base64字串
     * @return Bool 是否為Base64字串
     */
    function isbase64(string $b64string, bool $urlmode = false): bool {
        if ($urlmode) {
            if (preg_match("/[^0-9A-Za-z\-_]/", $b64string) > 0) return false;
        } else {
            if (preg_match("/[^0-9A-Za-z\+\/\=]/", $b64string) > 0) return false;
        }
        return true;
    }

    /**
     * @description: 字串轉字元陣列，可以處理中文
     * @param String str 源字串
     * @return Array 字元陣列
     */
    function mbStrSplit(string $str): array {
        return preg_split('/(?<!^)(?!$)/u', $str);
    }

    /**
     * @description: 批次替換指定字元
     * @param String string 規定被搜尋的字串
     * @param Array<String:String> findreplace 要替換的內容字典
     * 例如：Array("find" => "replace")
     * @param Bool geti 是否返回替換的數量，設定為 true 會返回陣列
     * @return String 經過替換後的字串
     * @return String,Int 經過替換後的字串和替換的數量
     */
    function replacestr(string $string, array $findreplace, bool $geti = false) {
        $find = array_keys($findreplace);
        $replace = array_values($findreplace);
        $newstring = str_replace($find, $replace, $string, $replacei);
        if ($geti) return [$newstring, $replacei];
        return $newstring;
    }

    /**
     * @description: 驗證是否為包含大小寫的MD5變體字串
     * @param String str MD5變體字串
     * @param String length 預期的字串長度
     * @return Bool 是否匹配
     */
    function is_rhash64(string $str, int $length = 32): bool {
        if (!ctype_alnum($str)) return false;
        if (strlen($str) != $length) return false;
        return true;
    }
}
