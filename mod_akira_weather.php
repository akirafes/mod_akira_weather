<?php

 

// no acceso directo

defined( '_JEXEC' ) or die( 'Restricted access' );

$layout = $params->get('layout','default');


require(JModuleHelper::getLayoutPath('mod_akira_weather',$layout));

?>