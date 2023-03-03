<?php

namespace App\Utils;

use App\Event\EventCalendar;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

class HelpCalendar {
    public static function transformDay ($day):string
    {
        $day = trim($day);
        switch ($day) {
            case "LU":
                return "Monday";
            case "MA":
                return "Tuesday";
            case "MI":
                return "Wednesday";
            case "JU":
                return "Thursday";
            case "VI":
                return "Friday";
            case "SA":
                return "Saturday";
            case "DO":
                return "Sunday";
            default:
                return "Not-day";
        }
    }

    /**
     * @throws Exception
     */
    public static function calculateEvents($event, $timezone_input, $timezone_output):array
    {
        $start_day = new DateTime($event->start_day, new DateTimeZone($timezone_input));
        $end_date = new DateTime($event->end_date, new DateTimeZone($timezone_input));
        $start_at = new DateTime($event->start_at, new DateTimeZone($timezone_input));
        $end_at = new DateTime($event->end_at, new DateTimeZone($timezone_input));
        $interval = new DateInterval('P1D');
        $days = explode(',',str_replace(' ','',$event->days));
        $array_days = [];
        foreach ($days as $day) {
            array_push($array_days,self::transformDay($day));
        }
        $event_id = $event->event_id;

        $generated_events = array();


        $start_day->setTimezone(new DateTimeZone($timezone_output));
        $end_date->setTimezone(new DateTimeZone($timezone_output));
        $start_at->setTimezone(new DateTimeZone($timezone_output));
        $end_at->setTimezone(new DateTimeZone($timezone_output));

        $datetime1 = new DateTime($event->start_date);
        $datetime2 = new DateTime($event->end_date);
        $interval = $datetime2->diff($datetime1);
        $days_number = $interval->format('%a');
        $counter = 0;
        while ($counter < $days_number) {
            if (in_array($start_day->format('l'), $array_days)) {
                $generated_event = new EventCalendar();
                //$generated_event->aula = $event->aula;
                $generated_event->days = explode(",", str_replace(" ", "", $event->days));
                $generated_event->title = $event->title;
                $generated_event->description = $event->description;
                $generated_event->event_id = $event_id;
                $generated_event->meeting_url = $event->meeting_url;

                $generated_event->end_date = $end_date->format('Y-m-d H:i:s');
                // $generated_event->start_day = $start_day->format('Y-m-d');
                $generated_event->start_at = $start_at->format('H:i:s');
                $generated_event->end_at = $end_at->format('H:i:s');
                // array_push($generated_events, $generated_event);

                foreach ($generated_event->days as $day) {
                    $dayOfWeek = self::transformDay($day);
                    $generated_event->start_date = date('Y-m-d', strtotime("next $dayOfWeek -1 day", strtotime($start_day->format('Y-m-d'))));
                    $generated_event->end_date = date('Y-m-d', strtotime("next $dayOfWeek", strtotime($start_day->format('Y-m-d ' . $generated_event->end_at))));
                    unset($generated_event->days);
                    $generated_events[] = $generated_event;
                }
            }
           $counter++;
        }
        return $generated_events;

    }
}

