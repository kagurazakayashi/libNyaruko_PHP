<?php
class NyaColor {
    /**
     * @description: 檢查顏色格式，並轉換為 ARGB 數字字串。
     * @param String inColor 輸入一個 ARGB/RGB 顏色，支援以下 6 種格式字串：
     * - 16 進位制: '0xFFFFFFFF', 'FFFFFFFF', 'FFFFFF', 'FFF'
     * - 10 進位制: '255,255,255,255', '255,255,255'
     * @param Int returnMode 返回資訊模式
     * @param Bool useHEX 是否使用 16 進位制，例如
     * - T: 十六進位制字串
     *     - 例如 'FFFFFFFF' : A=FF R=FF G=FF B=FF
     * - F: 數字字串（單色不足位補0）
     *     - 例如 '255255255255' : A=255 R=255 G=255 B=255
     * @param Bool alpha 是否保留 Alpha 值
     * - T: RGB 固定 12 位 數字字串 或 固定 8 位 十六進位制字串
     * - F: RGB 固定  9 位 數字字串 或 固定 6 位 十六進位制字串
     * @return String 按以上設定返回字串
     */
    function eColor(string $inColor = "000", bool $useHEX = false, bool $alpha = false): string {
        $color = [0, 0, 0, 0]; // ARGB
        if (strstr($inColor, ',') !== false) {
            $colorArr = explode(',', $inColor);
            $colorArrCount = count($colorArr);
            if ($colorArrCount >= 3 && $colorArrCount <= 4) {
                $modeARGB = $colorArrCount - 3;
                $color[0] = $modeARGB == 0 ? 255 : intval($colorArr[0]);
                $color[1] = intval($colorArr[0 + $modeARGB]);
                $color[2] = intval($colorArr[1 + $modeARGB]);
                $color[3] = intval($colorArr[2 + $modeARGB]);
            } else {
                return '';
            }
        } else {
            if (strlen($inColor) == 3) {
                $colorArr = str_split($inColor);
                for ($i = 0; $i < count($colorArr); $i++) {
                    $nowColor = $colorArr[$i];
                    $colorArr[$i] = $nowColor . $nowColor;
                }
                $inColor = implode('', $colorArr);
            }
            $colorArr = str_split($inColor, 2);
            $colorArrCount = count($colorArr);
            if ($colorArrCount >= 3 && $colorArrCount <= 5) {
                if (strcmp($colorArr[0], "0x") == 0) {
                    array_shift($colorArr);
                    $colorArrCount--;
                }
                $modeARGB = $colorArrCount - 3;
                $color[0] = $modeARGB == 0 ? 255 : hexdec($colorArr[0]);
                $color[1] = hexdec($colorArr[0 + $modeARGB]);
                $color[2] = hexdec($colorArr[1 + $modeARGB]);
                $color[3] = hexdec($colorArr[2 + $modeARGB]);
            } else {
                return '';
            }
        }
        $saveStr = ""; // 12
        $rmAlpha = $alpha ? 0 : 1;
        for ($i = $rmAlpha; $i < count($color); $i++) {
            $nowColor = $color[$i];
            if ($nowColor < 0 || $nowColor > 255) {
                return '';
            } else {
                if ($useHEX) {
                    $saveStr .= strtoupper(dechex($nowColor));
                } else {
                    $saveStr .= str_pad(strval($nowColor), 3, '0', STR_PAD_LEFT);
                }
            }
        }
        return $saveStr;
    }
    
    /**
     * @description: 將以整數儲存的顏色轉換會16進位制
     * @param Int inColor 整數顏色程式碼（上面的函式按預設值返回的結果樣式）
     * @param Bool alpha 是否包含 alpha 值
     * @return String 16進位制顏色
     */
    function dColor(int $inColor, bool $alpha = false) {
        $fullLength = $alpha ? 12 : 9;
        $colorStr = str_pad(strval($inColor), $fullLength, '0', STR_PAD_LEFT);
        $colorArr = str_split($colorStr, 3);
        for ($i = 0; $i < count($colorArr); $i++) {
            $color = intval($colorArr[$i]);
            $colorArr[$i] = dechex($color);
        }
        return strtoupper(implode('', $colorArr));
    }
}
