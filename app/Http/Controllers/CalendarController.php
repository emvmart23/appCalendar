<?php

namespace App\Http\Controllers;

use App\Event\EventCalendar;
use App\Utils\HelpCalendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function calcular(){
        $start_row = 1;

        $array_result = array();

        if (($csv_file = fopen(public_path("inputevents.csv"), "r")) !== FALSE) {
            while (($read_data = fgetcsv($csv_file, 1000, ",")) !== FALSE) {
                $column_count = count($read_data);

                $start_row++;


                if ($start_row > 2) {
                    $event = new EventCalendar();
                    $event->aula = $read_data[0];
                    $event->days = $read_data[1];
                    $event->start_day = $read_data[2];
                    $event->end_date = $read_data[3];
                    $event->start_at = $read_data[4];
                    $event->end_at = $read_data[5];
                    $event->meeting_url = $read_data[6];
                    $event->title = $read_data[7];
                    $event->description = $read_data[8];
                    $event->event_id = $read_data[9];
                    $array_result[] = $event;
                }
            }
            fclose($csv_file);
        }

        foreach ($array_result as $event) {
            $helpCalcular = new HelpCalendar();
            $generated_events = $helpCalcular->calculateEvents($event, 'America/Lima', 'UTC');
            echo '<pre>';
            print_r($generated_events);
            echo '</pre>';
        }
    }
}
