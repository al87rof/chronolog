<?php

namespace App\Service\Parser;


class Parser
{

    public function convert(){
        $tmpTxt = __DIR__ . '/results_old.csv';
        $date = '30-03-2025';

        $rows = [];

        if (($handle = fopen($tmpTxt, 'r')) !== false) {
            while (($data = fgetcsv($handle, 0, ';')) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        $result = "";
        foreach ( $rows as $item){
            $dateString = "$date ".$item[1];
            $dt = \DateTime::createFromFormat('d-m-Y H:i:s.u', $dateString);
            $milliseconds = ((int)$dt->format('U')) * 1000
                + (int)($dt->format('u') / 1000);

            $result.= $item[0].";".$milliseconds."\n";
        }
        $new = __DIR__ . '/results.csv';
        file_put_contents($new,$result);
    }


    public function convertWebScorer(): void
    {
        $tmpTxt = __DIR__ . '/results_old.csv';
        $date = '25-05-2025';

        $rows = [];

        if (($handle = fopen($tmpTxt, 'r')) !== false) {
            while (($data = fgetcsv($handle, 0, ';')) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        $result = "";
        foreach ( $rows as $item){
            if(strlen($item[1]) == 7){
                $dateString = "$date 00:".$item[1].'00';
            }
            elseif (strlen($item[1]) == 6){
                $dateString = "$date 00:0".$item[1].'00';
            }
            elseif (strlen($item[1]) == 8){
                $dateString = "$date ".$item[1].'.00';
            }
            else{
                $dateString = "$date ".$item[1].'00';
            }

            $dt = \DateTime::createFromFormat('d-m-Y H:i:s.u', $dateString);
            $milliseconds = ((int)$dt->format('U')) * 1000
                + (int)($dt->format('u') / 1000);

            $result.= $item[0].";".$milliseconds."\n";
        }
        $new = __DIR__ . '/results.csv';
        file_put_contents($new,$result);
    }



    public function exel(): void
    {
        $tmpTxt = __DIR__ . '/results_old.csv';
        $date = '18-10-2025';

        $rows = [];

        if (($handle = fopen($tmpTxt, 'r')) !== false) {
            while (($data = fgetcsv($handle, 0, ';')) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        $sort = [];
        foreach ( $rows as $item){
            [$h, $m, $s] = explode(':', "0".$item[1]);
            $totalSec = ($h * 3600 + $m * 60 + $s);
            $sort[$item[0]][] = $totalSec;
        }


        $result = "";
        foreach ( $sort as $riderId=>$laps){
            $time = 0;
            foreach ($laps as $lapNumber=>$lapTime){
                $time+= $lapTime;
                $dateString = gmdate('H:i:s',$time);
                $dateString = $date.' '.$dateString.'.000';
                $dt = \DateTime::createFromFormat('d-m-Y H:i:s.u', $dateString);
                $milliseconds = ((int)$dt->format('U')) * 1000
                    + (int)($dt->format('u') / 1000);

                $result.="$riderId;$milliseconds\n";
            }

        }
        $new = __DIR__ . '/results.csv';
        file_put_contents($new,$result);
    }


    public function exelWithMs(){
        $tmpTxt = __DIR__ . '/results_old.csv';
        $date = '28-03-2026';

        $rows = [];

        if (($handle = fopen($tmpTxt, 'r')) !== false) {
            while (($data = fgetcsv($handle, 0, ';')) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        $sort = [];
        foreach ($rows as $item){

            $timeStr = $item[1];

            [$timePart, $msPart] = explode('.', $timeStr);


            $parts = explode(':', $timePart);

            if (count($parts) == 2) {
                // mm:ss
                [$m, $s] = $parts;
                $h = 0;
            } else {
                // hh:mm:ss
                [$h, $m, $s] = $parts;
            }

            $totalMs = ($h * 3600 + $m * 60 + $s) * 1000 + (int)$msPart;

            $sort[$item[0]][] = $totalMs;
        }

        $result = "";
        foreach ($sort as $riderId => $laps){
            $time = 0;

            foreach ($laps as $lapNumber => $lapTime){
                $time += $lapTime;


                $sec = floor($time / 1000);
                $ms = $time % 1000;

                $dateString = gmdate('H:i:s', $sec) . '.' . str_pad($ms, 3, '0', STR_PAD_LEFT);
                $dateString = $date . ' ' . $dateString;

                $dt = \DateTime::createFromFormat('d-m-Y H:i:s.u', $dateString);

                $milliseconds = ((int)$dt->format('U')) * 1000
                    + (int)($dt->format('u') / 1000);

                $result .= "$riderId;$milliseconds\n";
            }
        }

        $new = __DIR__ . '/results.csv';
        file_put_contents($new, $result);
    }


}
