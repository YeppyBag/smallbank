<?php
class Config {
    /**
     * @var float $pointGain 0.01 = 1% ของยอดโอน
     */
    public static float $pointGain = 0.01;
    /**
     * @var float $extraPointGain 0.02 = 2% ของยอดโอน (ช่วง Event)
     */
    public static float $extraPointGain = 0.02;
    /**
     * @var int $reachGainPoint <p>แต้มถึง 1000</p>
     */
    public static float $reachGainPoint = 1000;
    /**
     * @var int $pointRequirement <p>แต้มที่ใช้ลด ค่าธรรมเนียม ต่อ 1 แต้ม</p>
     */
    public static int $pointRequirement = 1000;
    /**
     * @var int $eventDay
     *
     * วันที่คูณ แต้ม
     *
     * ตัวอย่าง:
     * - 1: จันทร์
     * - 2: อังคาร
     * - 3: พุธ
     */
    public static int $eventDay = 3;
    /**
     * @var int $eventTimeStart
     *
     * เริ่มช่วงคูณแต้ม
     *
     * ค่าคูณ แต้ม อิงจาก @var int $extraPointGain
     */
    public static int $eventTimeStart = 19;
    /**
     * @var int $eventTimeEnd
     *
     * หมดเวลาคูณแต้ม
     *
     */
    public static int $eventTimeEnd = 20;

    /**
     * @var int $pointExpireDays
     *
     * วันที่แต้มหมดอายุ
     *
     * จำนวนวันที่แต้มจะหมดอายุหลังจากได้รับ
     */
    public static int $pointExpireDays = 3;
    /**
     * @var float $depositMaximum
     *
     * จำนวนเงินสูงสุดที่สามารถทำการฝากได้
     */
    public static float $depositMaximum = 5000;
    /**
     * จำนวนวันที่จะหมดอายุในข้างหน้า
     * @var int จำนวนวัน
     */
    public static int $pointExpireInOneDay = 2;
}
