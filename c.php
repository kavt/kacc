<?php
/**
 * 从开放的天气预报服务中获得数据
 *
**/
header('Content-type: text/html;charset=utf-8');
 
$c = new SoapClient( 'http://www.webxml.com.cn/WebServices/WeatherWS.asmx?WSDL',
	array( 'trace' => true, 'exceptions' => true ) );
 
//var_dump( $c->__getFunctions() );
//var_dump( $c->__getTypes() );
//不需要参数的情况
//$pr =$c->getRegionProvince();
//var_dump( $pr->getRegionProvinceResult->string );
 
//带有参数的情况

echo "<pre>";

$scs = $c->getSupportCityString( array( 'theRegionCode' => '江苏' ) );


var_dump( $scs->getSupportCityStringResult->string );


 echo "</pre>";



//也可以这样做
//$we = $c->__call('getWeather', array( array( 'theCityCode' => '31119' ) ) );


//var_dump( $we );

?>