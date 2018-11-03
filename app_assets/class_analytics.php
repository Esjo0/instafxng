<?php

class Analytics {

    /**
     * Calculate the retention rate of a period selected
     *
     * @param $year
     * @param $type - monthly, quarterly, yearly
     * @param $period
     * @return int
     */
    public function customer_retention_rate($year = 1, $type = 1, $period = 1) {

        return 1;
    }

    /**
     * Calculate the dollar retention rate of a period selected
     *
     * @param $year
     * @param $type - monthly, quarterly, yearly
     * @param $period
     * @return int
     */
    public function dollar_retention_rate($year = 1, $type = 1, $period = 1) {
        return 1;
    }

    /**
     * Calculate the customer acquisition rate of a period selected
     *
     * @param $year
     * @param $type
     * @param $period
     * @return int
     */
    public function customer_acquisition_rate($year = 1, $type = 1, $period = 1) {
        return 1;
    }

    /**
     * Calculate the attrition rate of a period selected, i.e. the rate of losing
     * customers, it is the inverse of retention rate
     *
     * @param $year
     * @param $type
     * @param $period
     * @return int
     */
    public function customer_attrition_rate($year = 1, $type = 1, $period = 1) {
        return 1;
    }

}

$obj_analytics = new Analytics();