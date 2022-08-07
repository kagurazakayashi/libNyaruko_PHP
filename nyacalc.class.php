<?php
class NyaCalc {
    /**
     * @description: 將多個整數轉換為字串並確保最小值
     * @param Int min 最小值
     * @param Int *nums... 要進行轉換並運算的多個整數指標
     */
    function stringGreaterThanNum(int $min = 0, int &...$nums): void {
        $minstr = strval($min);
        foreach ($nums as &$num) {
            $num = $num > $min ? strval($num) : $minstr;
        }
    }

    /**
     * @description: 將一個整數中的每一位數字轉換為陣列
     * 例如輸入: 12345, 輸出: [5,4,3,2,1]
     * @param Int num 一個整數
     * @param Bool inverted 倒序輸出 [1,2,3,4,5]
     * @return Array 單個數字陣列
     */
    function intExtract(int $num, bool $inverted = false): array {
        $numI = 0;
        $numArr = [];
        while ($num > 0) {
            $numI = $num % 10;
            if ($inverted) {
                array_unshift($numArr, $numI);
            } else {
                array_push($numArr, $numI);
            }
            $num = intval($num / 10);
        }
        return $numArr;
    }
}
