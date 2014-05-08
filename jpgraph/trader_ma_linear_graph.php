<?php
/**
 * Created by PhpStorm.
 * User: young
 * Date: 1/30/14
 * Time: 3:04 PM
 */

session_start();
date_default_timezone_set('America/Los_Angeles');
$offset = date('Z');

function isJson($string) {
    $result = json_decode($string, true);

    // switch and check possible JSON errors
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            $error = ''; // JSON is valid
            break;
        case JSON_ERROR_DEPTH:
            $error = 'Maximum stack depth exceeded.';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            $error = 'Underflow or the modes mismatch.';
            break;
        case JSON_ERROR_CTRL_CHAR:
            $error = 'Unexpected control character found.';
            break;
        case JSON_ERROR_SYNTAX:
            $error = 'Syntax error, malformed JSON.';
            break;
        // only PHP 5.3+
        case JSON_ERROR_UTF8:
            $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
            break;
        default:
            $error = 'Unknown JSON error occured.';
            break;
    }

    if($error !== '') {
        // throw the Exception or exit
        echo $error;
       // echo $string;
    }else{
        // everything is OK
        //var_dump($result);
        echo 'OK';
    }


}


$output = file_get_contents('sampledata_rawdata.json');
$tanksize = 12;   // improve mpg for test user

//echo $output;
    isJson($output);
$mydata = json_decode($output, true);
    $datalength = sizeof($mydata);
    $totaldistance = 0;
    $tempdate = '';
    $period = 0;
    $tripcount = 0;
    $newtrip = true;
    $totalinterval = 0;
    $daydistance = 0;
    $preodometer = 0;
    $idle = 0;
    $unidle = 0;
    $timestring ='';

    $logdata = array();
    $yawdata = array ( array ('time', 'yaw_rate'));
    $speeddata = array ( array ('time', 'speed'));
    $ba_speeddata = array ( array ('time', 'Miles Per Hour over 20'));
    $lateralgdata = array ( array ('time', 'lateral_g'));
    $longgdata = array ( array ('time', 'long_g'));
    $accelgdata = array ( array ('time', 'Accel Pedal Pressure over 10'));
    $rpmdata = array ( array ('time',  'RPM over 2000'));
    $brakedata = array ( array ('time',  'PSI over 100'));


    $speedtotal = 0;
    $speedcount = 0;
    $ba_speedcount = 0;
    $over_speedcount = 0;
    $high_speedcount = 0;
    $speedmax = 0;
    $speedmiles = 0;
    $over_rpmcount = 0;
    $rpmcount=0;
    $maxrpm=0;
    $over_accelcount = 0;
    $accelcount=0;
    $maxaccel=0;
    $maxlat =  -1000;
    $minlat =  1000;
    $maxlong =  -1000;
    $minlong =  1000;
    $firstdata = '';
    $lastdata='';
    $drivinglog='';
    $tracklog='';
    $firstodo=0;
    $lastodo=0;
    $tempday='';
    $pre_timestampdate='';
    $triplogdata = array ();
    $triplog = array ();

    $first = true;

    foreach($mydata as $spdata){
        if(isset($spdata['timestamp'])){

            $getlog = true;
            $timestamp =  substr($spdata['timestamp'], 0, -3);
            $timestampdate =    date('Y-m-d H:i:s', $timestamp - $offset );
            $curdate = date('Y-m-d/H:i', $timestamp);     //interval : minute
            $curday = date('d', $timestamp);     //interval : minute
            //$curdate = date('Y-m-d/H:i:s', $timestamp);     //interval : second
            $temptimestampdate =  $timestampdate;


            if($tempday != $curday){
                $timestring = substr($curdate, 8, 16);
            }else{
                $timestring = substr($curdate, 11, 16);
            }

            // for distance calculation start //
            if(isset($spdata['odometer'])&&($spdata['odometer'] != null )){
                $odometer = $spdata['odometer'];
                if($first){
                    $firstdata = $spdata;
                    $firstodo = $odometer;
                    $lastdodo = $odometer;
                    $lastdata = $spdata;
                    $first = false;
                }else{

                    $lastdata = $spdata;
                    $lastodo = $odometer;

                }

                if($preodometer != $odometer){
                    $curdistance = ($preodometer ==0) ? 0 :  $odometer - $preodometer;
                    $daydistance += $curdistance;
                    $totaldistance += $curdistance;
                }

                if(($tempdate != $curdate)&&($tempdate !='')){
                    $period ++ ;
                    if($tempdate != 0){
//                            $dataArray[$tempdate] = $daydistance;
                    }
                    $daydistance = 0;
                }
                $preodometer = $odometer;


            }else{
                $getlog = false;
            }

            // distance calculation end


            //speed & yaw ..etc chart data   start
            if((isset($spdata['car_speed']))&&($spdata['car_speed']!=0)){
                $speedmiles = round($spdata['car_speed'] * 0.62137, 2);
                $speedtotal += $speedmiles;
                $speedcount ++;
                $speedmax = ($speedmax < $speedmiles) ? $speedmiles : $speedmax;

            }else{
                //   $getlog = false;
            }



            //speed & yaw data enc

            //for driving log
            if($getlog){
                if($timestampdate){

                    if($pre_timestampdate){
                        $timet = explode(':', substr($pre_timestampdate, -5));
                        $timec = explode(':', substr($timestampdate, -5)) ;

                        if(($timec[0] == ($timet[0] + 1) )||($timet[0]==$timec[0])||(($timec[0] =='00' )&& ($timet[0]=='59') )){

                        }else{
                            $tripcount++;
                        }
                    }else{
                        $tripcount++;
                    }


                    $timeformat = str_replace(' ',  'T', $timestampdate).'.000Z';             // T time format


                    $drivinglog[$timestampdate]['time'] = $timeformat;
                    $drivinglog[$timestampdate]['tripcount'] = $tripcount;
                    $drivinglog[$timestampdate]['odometer'] = $odometer;
                    $drivinglog[$timestampdate]['speed'] = $speedmiles;
                    $pre_timestampdate = $timestampdate;

                    //track log latitude / logtitude
                    $localtime = date('Y-m-d h:i:s A', strtotime($timeformat));

                    //new triplogdata
                    if(isset($triplogdata[$tripcount]['starttime'])){
                        $triplogdata[$tripcount]['endtime'] = $timestamp ;
                        $triplogdata[$tripcount]['lastodometer'] = $odometer;
                    }else{
                        $triplogdata[$tripcount]['starttime'] = $timestamp;
                        $triplogdata[$tripcount]['startodometer'] = $odometer;
                    }

                    if(isset($triplogdata[$tripcount]['maxspeed'])){                    //new 0407
                        if($speedmiles > $triplogdata[$tripcount]['maxspeed']) {
                            $triplogdata[$tripcount]['maxspeed'] =  $speedmiles;
                        }
                    }else{
                        $triplogdata[$tripcount]['maxspeed'] =  $speedmiles;
                    }

                }

            }


            // fuel level check
            if((isset($spdata['fuel_level']))&&($spdata['fuel_level'] !=0 )){
                $fuel_change [$tripcount][] = $spdata['fuel_level'];
            }

            $tempdate = $curdate;
            $tempday = $curday;
            $totalinterval ++ ;

        }


    }

$linearreg  = trader_linearreg($fuel_change[1], 30);
$traderma = trader_ma($fuel_change[1], 30);

//    echo 'fuel change <pre>';   print_r($fuel_change);   echo '</pre>';
//    echo 'linear reg <pre>';   print_r(trader_linearreg($fuel_change[1]));   echo '</pre>';

for($i=30 ; $i<sizeof($fuel_change[1]);$i++){
//    echo $i.'--'. $fuel_change[1][$i].' /  '.$linearreg[$i].' /  '.$traderma[$i].'<br />';
}



/*for chart */
require_once ('jpgraph/src/jpgraph.php');
require_once ('jpgraph/src/jpgraph_line.php');

$datay1 = array_slice($fuel_change[1], 30, 200);
$datay2 =array_slice($traderma, 30, 200);
$datay3 = array_slice($linearreg, 30, 200);

// Setup the graph
$graph = new Graph(1400,550);
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;

$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(false);
$graph->title->Set('Filled Y-grid');
$graph->SetBox(false);

$graph->img->SetAntiAliasing();

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
// $graph->xaxis->SetTickLabels(array('A','B','C','D'));
$graph->xgrid->SetColor('#E3E3E3');

// Create the first line
$p1 = new LinePlot($datay1);
$graph->Add($p1);
$p1->SetColor("#FF0000");
$p1->SetLegend('Line 1');

// Create the second line
$p2 = new LinePlot($datay2);
$graph->Add($p2);
$p2->SetColor("#00FF00");
$p2->SetLegend('Line 2');

// Create the third line
$p3 = new LinePlot($datay3);
$graph->Add($p3);
$p3->SetColor("#0000FF");
$p3->SetLegend('Line 3');

$graph->legend->SetFrameWeight(1);

// Output line
$graph->Stroke();




