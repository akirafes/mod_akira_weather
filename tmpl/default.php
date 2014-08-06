<?php
// no acceso directo
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
//$document->addScript(Juri::base().'/modules/mod_solaris_flickr/vendors/colorbox/jquery.colorbox-min.js');
$document->addStyleSheet(Juri::base().'/modules/mod_akira_weather/css/weather.css');
$document->addStyleSheet(Juri::base().'/modules/mod_akira_weather/vendors/weather-icons-master/css/weather-icons.css');

$apiKey           = $params->get('apiKey');
$stationId        = $params->get('stationId');
$timeFormat       = $params->get('timeFormat');
$temperatureScale = $params->get('temperatureScale');
$language         = $params->get('language');
$displaySunset    = $params->get('displaySunset');
$module_class_su  = $params->get('moduleClassSu');


$json_string = file_get_contents("http://api.wunderground.com/api/".$apiKey."/conditions/astronomy/q/pws:".$stationId.".json");
$parsed_json = json_decode($json_string);
//$iconos = ['Drizzle','Rain','Snow','Snow Grains','Ice Crystals','Ice Pellets','Hail','Mist','Fog','Fog Patches','Smoke','Volcanic Ash','Widespread Dust','Sand','Haze','Spray','Dust Whirls','Sandstorm','Low Drifting Snow','Low Drifting Widespread Dust','Low Drifting and','Blowing Snow','Blowing Widespread Dust','Blowing Sand','Rain Mist','Rain Showers','Snow Showers','Snow Blowing Snow Mist','Ice Pellet Showers','Hail Showers','Small Hail Showers','Thunderstorm','Thunderstorms and Rain','Thunderstorms nd Snow','Thunderstorms and Ice PelletsThunderstorms with Hail','Thunderstorms with Small Hail','Freezing Drizzle','Freezing Rain','Freezing Fog'];
$trans = [array('partlycloudy','sunny-overcast'),array('mostlycloudy','cloudy'),array('Drizzle','showers'),array('Ice Crystals','Snow'),array('Ice Pellets','Snow'),array('Mist','Fog'),array('Thunderstorms and Rain','thunderstorm')];
$iconoString = $parsed_json->current_observation->weather;
$icono = $parsed_json->current_observation->icon;
if($temperatureScale == "f"){
    $temperature = round($parsed_json->current_observation->temp_f);
    $feelslike = round($parsed_json->current_observation->feelslike_f);
} else {
    $temperature = round($parsed_json->current_observation->temp_c);
    $feelslike = round($parsed_json->current_observation->feelslike_c);
}

$sunrise = new DateTime();
$sunrise->setTime($parsed_json->sun_phase->sunrise->hour,$parsed_json->sun_phase->sunrise->minute);
$sunset  = new DateTime();
$sunset->setTime($parsed_json->sun_phase->sunset->hour,$parsed_json->sun_phase->sunset->minute);
if($timeFormat == "am"){
    $sunsetF = $sunset->format("h:i A");
    $sunriseF = $sunrise->format("h:i A");
} else {
    $sunsetF = $sunset->format("H:i");
    $sunriseF = $sunrise->format("H:i");
}
foreach($trans as $tran){
    if($icono == $tran[0]){$icono = $tran[1]; }
}

?>

<div class="<?php echo $module_class_su;?>">
<div class="weather-widget clearfix">
    <div class="current-weather clearfix">
        <div class="currently">
            <div class="currently-inner"><span class="label-current-weather">Currently</span> <span
                    class="wi wi-day-<?php echo $icono; ?> label-currently"><?php echo $iconoString; ?></span></div>
            <!-- /currently-inner --></div>
        <!-- /currently -->
        <div class="current-temp">
            <div class="current-temp-inner">
                <h4 class="temp"><?php echo $temperature; ?><span class="degrees-symbol color-text">º</span></h4>
                <!-- /temp -->
                <div class="feels-like">Feels like <span
                        class="degrees-feels-like color-text"><?php echo $feelslike; ?>º</span></div>
                <!-- /feels-like --></div>
            <!-- /current-temp-inner --></div>
        <!-- /current-temp --></div>
    <!-- /current-weather -->
    <?php if($displaySunset == "yes") {?>
    <div class="sun-activity clearfix"><span class="label-sun-activity">Sunrise / Sunset</span>

        <div class="sun-activity-hours clearfix"><span class="sunrise"><?php echo $sunriseF ?></span>
            <span class="sunset"><?php echo $sunsetF ?></span></div>
        <!-- /sun-activity-hours --></div>
    <!-- /sun-activity -->

    <div class="full-report btn-row clearfix"><a href="#" class="btn btn-default btn-full-report">Full Report</a></div>
    <?php } ?>
    <!-- /btn-row --></div>
</div>