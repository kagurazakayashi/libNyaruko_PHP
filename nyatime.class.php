<?php
class NyaTime {
    /**
     * @description: 獲得當前時間
     * @param String timezone PHP時區程式碼（可選，預設為配置檔案中配置的時區）
     * @param Int settime 自定義時間戳（秒）
     * @return Array [Datetime,String] 返回時間日期物件和時間日期字串
     */
    function getdatetime(string $timezone = null, int $settime = null) {
        if ($timezone) {
            $timezone_out = date_default_timezone_get();
            date_default_timezone_set($timezone);
        }
        $timestamp = $settime ? $settime : time();
        $timestr = date('Y-m-d H:i:s', $timestamp);
        if ($timezone) date_default_timezone_set($timezone_out);
        return [$timestamp, $timestr];
    }

    /**
     * @description: 獲得當前時間（可直接用於SQL語句）
     * @param Int settime 自定義時間戳（秒）
     * @return String 時間日期字串
     */
    function getnowtimestr(int $settime = -1): string {
        $timestamp = ($settime > 0) ? $settime : time();
        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * @description: 獲得毫秒級時間戳
     */
    function millisecondtimestamp() {
        $time = explode(" ", strval(microtime()));
        $time = $time[1] . (intval($time[0]) * 1000);
        $time2 = explode(".", $time);
        $time = $time2[0];
        return $time;
    }

    /**
     * @description: 檢查時間戳差異是否大於指定值
     * @param Int timestamp1 秒級時間戳1
     * @param Int timestamp2 秒級時間戳2
     * @param Int max 允許的最大差異
     * @return Bool 時間戳差異是否大於指定值
     */
    function timestampdiff(int $timestamp1, int $timestamp2, int $max): bool {
        global $nlcore;
        $timestampabs = abs($timestamp1 - $timestamp2);
        return $timestampabs <= $max;
    }
}
