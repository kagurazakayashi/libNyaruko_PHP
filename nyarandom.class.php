<?php
class NyaRandom {
    /**
     * @description: 隨機將字串中的每個字轉換為大寫或小寫
     * @param String str 要進行隨機大小寫轉換的字串
     * @return String 轉換後的字串
     */
    function randstrto(string $str): string {
        $strarr = str_split($str);
        for ($i = 0; $i < count($strarr); $i++) {
            if (rand(0, 1) == 1) {
                $strarr[$i] = strtoupper($strarr[$i]);
            } else {
                $strarr[$i] = strtolower($strarr[$i]);
            }
        }
        return implode("", $strarr);
    }

    /**
     * @description: 進行MD5後進行隨機大小寫轉換
     * @param String str 明文
     * @return String 轉換後的字串
     */
    function rhash32(string $str): string {
        return $this->randstrto(md5($str));
    }

    /**
     * @description: 生成一段隨機文字
     * @param Int len 生成長度
     * @param String chars 從此字串中抽取字元
     * @return String 新生成的隨機文字
     */
    function randstr(int $len = 64, string $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'): string {
        mt_srand($this->seed());
        $password = "";
        while (strlen($password) < $len) $password .= substr($chars, (mt_rand() % strlen($chars)), 1);
        return $password;
    }

    /**
     * @description: 生成隨機雜湊值
     * @param String salt 鹽（可選）
     * @param Boolean randstrto 將雜湊結果隨機大小寫
     * @return String 生成的字串
     */
    function randhash(string $salt = "", bool $randstrto = true): string {
        $data = (float)microtime() . $salt . $this->randstr(16);
        $data = md5($data);
        return $randstrto ? $this->randstrto($data) : $data;
    }

    /**
     * @description: 生成隨機數發生器種子
     * @param String salt 鹽（可選）
     * @return String 种子
     */
    function seed(string $salt = ""): string {
        $newsalt = (float)microtime() * 1000000 * getmypid();
        return $newsalt . $salt;
    }
}
