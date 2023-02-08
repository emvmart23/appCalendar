<?php

namespace App\Utils;

use App\Event\EventCalendar;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

class HelpCalendar {
    function transformDay($day):string
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
    function calculateEvents($event, $timezone_input, $timezone_output):array
    {
        $start_day = new DateTime($event->start_day, new DateTimeZone($timezone_input));
        $end_date = new DateTime($event->end_date, new DateTimeZone($timezone_input));
        $start_at = new DateTime($event->start_at, new DateTimeZone($timezone_input));
        $end_at = new DateTime($event->end_at, new DateTimeZone($timezone_input));
        $interval = new DateInterval('P1D');
        $days = explode(',', $this->transformDay($event->days));
        $event_id = $event->event_id;

        $generated_events = array();

        // Set timezone for output
        $start_day->setTimezone(new DateTimeZone($timezone_output));
        $end_date->setTimezone(new DateTimeZone($timezone_output));
        $start_at->setTimezone(new DateTimeZone($timezone_output));
        $end_at->setTimezone(new DateTimeZone($timezone_output));

        // Check if eventCalendar is within the selected days
        while ($start_day <= $end_date) {
            if (in_array($start_day->format('l'), $days)) {
                $generated_event = new EventCalendar();
                $generated_event->aula = $event->aula;
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
                    $dayOfWeek = $this->transformDay($day);
                    $generated_event->start_date = date('Y-m-d', strtotime("next $dayOfWeek -1 day", strtotime($start_day->format('Y-m-d'))));
                    $generated_event->end_date = date('Y-m-d', strtotime("next $dayOfWeek", strtotime($start_day->format('Y-m-d ' . $generated_event->end_at))));
                    unset($generated_event->days);
                    $generated_events[] = $generated_event;
                }
            }
            $start_day->add($interval);
        }
        return $generated_events;
    }
}

