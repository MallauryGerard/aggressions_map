<?php

namespace App\Traits;

use DateTime;
use DateTimeZone;

trait FormatDate {

    public function getFormatDate($type = 'created_at', $onlyDate = false) {
        // OnlyDate is usually use for anonymous content to avoid "profiling"
        $timezone = new DateTimeZone('Europe/Brussels');
        $datetime = new Datetime($this->created_at, $timezone);

        if ($type != 'created_at') {
            $datetime = new Datetime($this->$type, $timezone);
        }

        $now = new Datetime();
        $datetime->setTimezone($timezone);
        $now->setTimezone($timezone);
        $interval = $now->diff($datetime);
        if ($interval->days > 1) {
            if ($onlyDate) {
                $date = 'le ' . $datetime->format('d F Y');
            } else {
                $date = 'le ' . $datetime->format('d F Y à H:i');
            }
        } else {
            if ($interval->d == 1) {
                if ($onlyDate) {
                    $date = 'hier';
                } else {
                    $date = 'hier à ' . $datetime->format('H:i');
                }
            } else {
                if ($interval->h >= 1) {
                    if ($onlyDate) {
                        $date = 'aujourd\'hui';
                    } else {
                        $date = 'il y a environ ' . $interval->format('%h');
                        $date .= ($interval->h == 1) ? ' heure' : ' heures';
                    }
                } else {
                    if ($interval->i > 1) {
                        if ($onlyDate) {
                            $date = 'il y a moins d\'une heure';
                        } else {
                            $date = 'il y a ' . $interval->format('%i');
                            $date .= ($interval->m == 1) ? ' minute' : ' minutes';
                        }
                    } else {
                        $date = 'à l\'instant';
                    }
                }
            }
        }
        return $this->translateMonthsInFrench($date);
    }

    private function translateMonthsInFrench(String $date){
        $englishMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $frenchMonths = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
        return str_replace($englishMonths, $frenchMonths, $date);
    }

}
