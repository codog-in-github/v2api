<?php
namespace App\Utils\Order;


/**
 * OrderNum类用于处理订单号的相关操作，包括生成下一个订单号、检查是否存在禁用的数字序列等。
 * @usage
 *     $orderNum = new OrderNum(1234);
 *     $orderNum->isAvailable(); // 检查1234是否可用
 *     $orderNum->getNext(); // 获取下一个可用订单号
 *     $orderNum->toInt() // 将订单号转换为整数
 */
class OrderNum {

    /**
     * @var int[]
     */
    protected array $numArr;

    /**
     * 定义禁用的数字序列，这些序列不应该出现在订单号中 （逆序）
     * @var int[][]
     */
    // 查询串需要倒置
    protected static array $disabled = [
        [3], [4], [9], [1, 0, 0]
    ];


    /**
     * 构造函数，初始化订单号的数字数组。
     * @param int $num 订单号的整数形式。
     */
    public function __construct(int $num) {
        $this->numArr = $this->splitInt($num);
    }

    /**
     * 判断订单号是否可用。
     * @return bool
     */
    public function isAvailable(): bool{
        return $this->disabledIndexOf() >= 0;
    }

    /**
     * 检查当前订单号中是否存在禁用的数字序列，若存在返回第一个禁用序列的索引，否则返回-1。
     * @return int 禁用序列的索引或-1。
     */
    protected function disabledIndexOf(): int {
        foreach (self::$disabled as $disabledSeq) {
            $disabledLength = count($disabledSeq);
            for ($i = 0; $i <= count($this->numArr) - $disabledLength; $i++) {
                $match = true;
                for ($j = 0; $j < $disabledLength; $j++) {
                    if ($this->numArr[$i + $j] !== $disabledSeq[$j]) {
                        $match = false;
                        break;
                    }
                }
                if ($match) {
                    return $i;
                }
            }
        }
        return -1;
    }

    /**
     * 为订单号的指定位置增加指定的数值。
     * @param int $value 要增加的数值。
     * @param int $index 增加的位置，若不指定则默认从最左边开始。
     * @return OrderNum 返回更新后的订单号实例。
     */
    protected function add(int $value, int $index = 0): self {
        if($index < 0) {
            return $this;
        }
        $maxIndex = count($this->numArr) - 1;
        $last = $value;
        for($i = 0; $i < $index; $i++) {
            if($i > $maxIndex) {
                $maxIndex += 1;
                $this->numArr[] = 0;
            } else {
              $this->numArr[$i] = 0;
            }
        }
        for($i = $index; $last > 0; $i++) {
            if($i > $maxIndex) {
                $maxIndex += 1;
                $this->numArr[] = 0;
            }
            $currentValue = $last % 10 + $this->numArr[$i];
            $last = (int) ($last / 10);
            if($currentValue > 10) {
                $currentValue -= 10;
                $last += 1;
            }
            $this->numArr[$i] = $currentValue;
        }
        return $this;
    }

    /**
     * 将一个整数逆序拆分为单个数字的数组。
     * @param int $num 要拆分的整数。
     * @return int[] 拆分后的数字数组。
     */
    protected function splitInt(int $num): array {
        $numArr = [];
        while ($num > 0) {
            $numArr[] = $num % 10;
            $num = (int)($num / 10);
        }
        return $numArr;
    }

    /**
     * 将订单号的数字数组转换回整数形式。
     * @return int 订单号的整数形式。
     */
    public function toInt(): int {
        $value = 0;
        $pow = 1;
        for ($i = 0; $i < count($this->numArr); $i++) {
            $value += $this->numArr[$i] * $pow;
            $pow *= 10;
        }
        return $value;
    }

    /**
     * 生成下一个有效的订单号。
     * @return OrderNum 下一个订单号的实例。
     */
    public function next(): self {
        $newInstance = clone $this;
        $newInstance->add(1);
        while(($disabledIndex = $newInstance->disabledIndexOf()) !== -1) {
            $newInstance->add(1, $disabledIndex);
        }
        return $newInstance;
    }
}
