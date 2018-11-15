<?php

class Analytics {

    /**
     * @param $year
     * @param $period
     * @return array
     */
    public function get_from_to_dates($year, $period) {

        $prev_year = $year - 1;

        switch ($period) {
            case '1':
                $title = "January " . $year;
                $from_date = $year . "-01-01";
                $to_date = $year . "-01-31";
                $prev_from_date = $prev_year . "-12-01";
                $prev_to_date = $prev_year . "-12-31";
                break;
            case '2':
                $title = "February " . $year;
                $from_date = $year . "-02-01";
                $to_date = $year . "-02-29";
                $prev_from_date = $year . "-01-01";
                $prev_to_date = $year . "-01-31";
                break;
            case '3':
                $title = "March " . $year;
                $from_date = $year . "-03-01";
                $to_date = $year . "-03-31";
                $prev_from_date = $year . "-02-01";
                $prev_to_date = $year . "-02-29";
                break;
            case '4':
                $title = "April " . $year;
                $from_date = $year . "-04-01";
                $to_date = $year . "-04-30";
                $prev_from_date = $prev_year . "-03-01";
                $prev_to_date = $prev_year . "-03-31";
                break;
            case '5':
                $title = "May " . $year;
                $from_date = $year . "-05-01";
                $to_date = $year . "-05-31";
                $prev_from_date = $year . "-04-01";
                $prev_to_date = $year . "-04-30";
                break;
            case '6':
                $title = "June " . $year;
                $from_date = $year . "-06-01";
                $to_date = $year . "-06-30";
                $prev_from_date = $year . "-05-01";
                $prev_to_date = $year . "-05-31";
                break;
            case '7':
                $title = "July " . $year;
                $from_date = $year . "-07-01";
                $to_date = $year . "-07-31";
                $prev_from_date = $year . "-06-01";
                $prev_to_date = $year . "-06-30";
                break;
            case '8':
                $title = "August " . $year;
                $from_date = $year . "-08-01";
                $to_date = $year . "-08-31";
                $prev_from_date = $year . "-07-01";
                $prev_to_date = $year . "-07-31";
                break;
            case '9':
                $title = "September " . $year;
                $from_date = $year . "-09-01";
                $to_date = $year . "-09-30";
                $prev_from_date = $year . "-08-01";
                $prev_to_date = $year . "-08-31";
                break;
            case '10':
                $title = "October " . $year;
                $from_date = $year . "-10-01";
                $to_date = $year . "-10-31";
                $prev_from_date = $year . "-09-01";
                $prev_to_date = $year . "-09-30";
                break;
            case '11':
                $title = "November " . $year;
                $from_date = $year . "-11-01";
                $to_date = $year . "-11-30";
                $prev_from_date = $year . "-10-01";
                $prev_to_date = $year . "-10-31";
                break;
            case '12':
                $title = "December " . $year;
                $from_date = $year . "-12-01";
                $to_date = $year . "-12-31";
                $prev_from_date = $year . "-11-01";
                $prev_to_date = $year . "-11-30";
                break;
            case '1-12':
                $title = "Annual " . $year;
                $from_date = $year . "-01-01";
                $to_date = $year . "-12-31";
                $prev_from_date = $prev_year . "-01-01";
                $prev_to_date = $prev_year . "-12-31";
                break;
            case '1-6':
                $title = "First Half " . $year;
                $from_date = $year . "-01-01";
                $to_date = $year . "-06-30";
                $prev_from_date = $prev_year . "-07-01";
                $prev_to_date = $prev_year . "-12-31";
                break;
            case '7-12':
                $title = "Second Half " . $year;
                $from_date = $year . "-07-01";
                $to_date = $year . "-12-31";
                $prev_from_date = $year . "-01-01";
                $prev_to_date = $year . "-06-30";
                break;
            case '1-3':
                $title = "First Quarter " . $year;
                $from_date = $year . "-01-01";
                $to_date = $year . "-03-31";
                $prev_from_date = $prev_year . "-10-01";
                $prev_to_date = $prev_year . "-12-31";
                break;
            case '4-6':
                $title = "Second Quarter " . $year;
                $from_date = $year . "-04-01";
                $to_date = $year . "-06-30";
                $prev_from_date = $year . "-01-01";
                $prev_to_date = $year . "-03-31";
                break;
            case '7-9':
                $title = "Third Quarter " . $year;
                $from_date = $year . "-07-01";
                $to_date = $year . "-09-31";
                $prev_from_date = $year . "-04-01";
                $prev_to_date = $year . "-06-30";
                break;
            case '10-12':
                $title = "Fourth Quarter " . $year;
                $from_date = $year . "-10-01";
                $to_date = $year . "-12-31";
                $prev_from_date = $year . "-07-01";
                $prev_to_date = $year . "-09-31";
                break;
        }

        $dates = array("period_title" => $title, "from_date" => $from_date, "to_date" => $to_date, "prev_from_date" => $prev_from_date, "prev_to_date" => $prev_to_date);
        return $dates;
    }

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

    public function get_client_funding_in_period($user_code, $from_date, $to_date) {
        global $db_handle;

        $query = "SELECT SUM(ud.real_dollar_equivalent) AS sum_funding
            FROM user_deposit AS ud
            INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
            WHERE ui.user_code = '$user_code' AND ud.status = '8' AND STR_TO_DATE(ud.order_complete_time, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result)[0]['sum_funding'];

        return $fetched_data;
    }

    public function get_quarter_code($month) {

        if ($month >= 1 && $month <= 3) {
            $current_quarter = '1-3';
        } else if ($month >= 4 && $month <= 6) {
            $current_quarter = '4-6';
        } else if ($month >= 7 && $month <= 9) {
            $current_quarter = '7-9';
        } else if ($month >= 10 && $month <= 12) {
            $current_quarter = '10-12';
        }

        return $current_quarter;
    }

    public function get_half_year_code($month) {

        if ($month >= 1 && $month <= 6) {
            $current_half_year = '1-6';
        } else if ($month >= 7 && $month <= 12) {
            $current_half_year = '7-12';
        }

        return $current_half_year;
    }

    public function get_retention_analytics() {
        global $db_handle;

        $current_year = date('Y');
        $main_current_month = date('m');
        $today = date('Y-m-d');

        $current_quarter = $this->get_quarter_code($main_current_month);
        $current_half_year = $this->get_half_year_code($main_current_month);
        $current_year_code = "1-12";

        $month_title = $this->get_from_to_dates($current_year, $main_current_month)['period_title'];
        $quarter_title = $this->get_from_to_dates($current_year, $current_quarter)['period_title'];
        $half_year_title = $this->get_from_to_dates($current_year, $current_half_year)['period_title'];
        $year_title = $this->get_from_to_dates($current_year, $current_year_code)['period_title'];

        $query = "SELECT * FROM retention_analytics WHERE date_today = '$today' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $retention_analytics = $db_handle->fetchAssoc($result)[0];

        $titles = array("month_title" => $month_title, "quarter_title" => $quarter_title, "half_year_title" => $half_year_title, "year_title" => $year_title);

        return array_merge($retention_analytics, $titles);
    }

    public function get_onboarding_analytics() {
        global $db_handle;

        $current_year = date('Y');
        $main_current_month = date('m');
        $today = date('Y-m-d');

        $current_quarter = $this->get_quarter_code($main_current_month);
        $current_half_year = $this->get_half_year_code($main_current_month);
        $current_year_code = "1-12";

        $month_title = $this->get_from_to_dates($current_year, $main_current_month)['period_title'];
        $quarter_title = $this->get_from_to_dates($current_year, $current_quarter)['period_title'];
        $half_year_title = $this->get_from_to_dates($current_year, $current_half_year)['period_title'];
        $year_title = $this->get_from_to_dates($current_year, $current_year_code)['period_title'];

        $query = "SELECT * FROM onboarding_analytics WHERE date_today = '$today' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $onboarding_analytics = $db_handle->fetchAssoc($result)[0];

        $titles = array("month_title" => $month_title, "quarter_title" => $quarter_title, "half_year_title" => $half_year_title, "year_title" => $year_title);

        return array_merge($onboarding_analytics, $titles);
    }

}

$obj_analytics = new Analytics();