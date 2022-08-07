<?php
class NyaPath {
    /**
     * @description: 替換字串中某個字元的多個連續字元，轉換成一個
     * 例如： "///" -> "/"
     * @param String str 原字串
     * @param String chr 要合併的字元
     * @return String 替換後的字元
     */
    function mergerepeatchar(string $str, string $chr): string {
        $chararr = str_split($str);
        $newchararr = [];
        $oldchar = "";
        foreach ($chararr as $nowchar) {
            if (!($nowchar == $oldchar && $chr == $nowchar)) {
                array_push($newchararr, $nowchar);
                $oldchar = $nowchar;
            }
        }
        return implode('', $newchararr);
    }

    /**
     * @description: 轉換為系統路徑
     * 將路徑字元 '/' 和 '\' 統一轉換為當前系統使用的路徑字元
     * @param String path 路徑字串
     * @return String 轉換後的路徑字串
     */
    function dirsep(string $path): string {
        $newpath = str_replace("\\", DIRECTORY_SEPARATOR, $path);
        $newpath = str_replace("/", DIRECTORY_SEPARATOR, $newpath);
        return $this->mergerepeatchar($newpath, DIRECTORY_SEPARATOR);
    }

    /**
     * @description: 轉換為網址路徑
     * 將路徑字元 '\' 轉換為 URL 用的 '/'
     * @param String path 路徑字串
     * @return String 轉換後的路徑字串
     */
    function urlsep(string $path): string {
        $newpath = str_replace("\\", "/", $path);
        return $this->mergerepeatchar($newpath, "/");
    }

    /**
     * @description: 自動清除路徑中的資料夾字元(../)，可以在路徑的任何位置。
     * @param String path 路徑字串
     * @param Int level 如果提供此數值，會改為手動向上父級多少級
     * @return String 轉換後的路徑字串
     */
    function parentfolder(string $path, int $level = -1): string {
        $newpath = $this->dirsep($path);
        $endchar = substr($newpath, -1);
        if ($endchar != DIRECTORY_SEPARATOR) $endchar = "";
        $startchar = substr($newpath, 0, 1);
        if ($startchar != DIRECTORY_SEPARATOR) $startchar = "";
        $newpath = substr($newpath, strlen($startchar), strlen($newpath) - 1 - strlen($endchar));
        $patharr = explode(DIRECTORY_SEPARATOR, $newpath);
        $newpatharr = [];
        if ($level == -1) {
            foreach ($patharr as $dir) {
                if ($dir == "..") {
                    array_pop($newpatharr);
                } else {
                    array_push($newpatharr, $dir);
                }
            }
        } else {
            $newpatharr = $patharr;
            for ($i = 0; $i < $level; $i++) {
                array_pop($newpatharr);
            }
        }
        if (count($newpatharr) == 0) return DIRECTORY_SEPARATOR;
        $newpath = implode(DIRECTORY_SEPARATOR, $newpatharr);
        return $startchar . $newpath . $endchar;
    }

    /**
     * @description: 將父資料夾字元(../)移除，並返回有多少層
     * 例如： "/server/path/../../" -> [2,"/server/path/"]
     * @param String path 路徑字串
     * @return Array<Int,String> [層數,轉換後的路徑]
     */
    function parentfolderlevel(string $path): array {
        $pdirstr = ".." . DIRECTORY_SEPARATOR;
        $newpath = str_replace($pdirstr, "", $path, $ri);
        return [$ri, $newpath];
    }

    /**
     * @description: 過濾字串中的非法字元
     * @param String str 源字串
     * @param Bool errdie 如果出錯則完全中斷執行，返回錯誤資訊JSON
     * @param Bool dhtml 是否將HTML程式碼也視為非法字元
     * @return String 經過過濾的字元
     */
    function safestr($str, $errdie = true, $dhtml = false): string {
        $ovalue = $str;
        $str = stripslashes($str);
        if ($str != $ovalue && $errdie) {
            return '';
        }
        if ($dhtml) {
            $str = htmlspecialchars($str);
            if ($str != $ovalue && $errdie) {
                return '';
            }
        }
        return $str;
    }
}
