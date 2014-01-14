<?php
error_reporting(0); 
//判断是否提交
if(empty($_POST['sub'])){

	echo '请重新提交';
	die;
}


$city = $_POST['city'];//城市

$name = $_POST['name'];//姓名



 
//WEBSERVICES 
$c = new SoapClient( 'http://58.246.30.10:811/Service.asmx?WSDL');
$privateKey = "4R18S7WSBKSRX2DTWXR8S1ENDI3JCX0Z";// 16 24 32固定密钥

$str_key = uniqid(rand());
$iv = substr(md5($str_key), 0, 16);//16位初始向量
//echo "随机初始向量".$iv."<br>";

$data   =  time();	//现在时间戳
//echo "现在的时间戳".$data."<br>";

//加密
$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $privateKey, $data, MCRYPT_MODE_CBC, $iv);
$deco = base64_encode($encrypted);

$go = '16'.$iv.$deco;	//接口请求校验码
$a = '<?xml version="1.0" encoding="UTF-8"?>
<Request>
 <CheckInfo>
　　<CheckUser>CmdaOrg</CheckUser>
　　　<CheckCode>';
	$a .=$go.'</CheckCode>
 </CheckInfo>
　　<Operation>Cmda.AssessDoctorList</Operation>
　　<Info>
　　　　<Province>';
		$a .= $city.'</Province>
　　　　<DoctorName><![CDATA[';
		$a .= $name.']]></DoctorName>
　　</Info>
</Request>';






$scs = $c->Response( array( 'RequestInfo' => $a) ); //发送 

$go = $scs->ResponseResult;							//返回


//print_r($go);



	$xml = simplexml_load_string( $go ); //解析XML字串

	$num = $xml->Result->Code;
	if($num == '0'){
	foreach( $xml ->  Info->DoctorList->Doctor as $book) 
	{
		//1.姓名
		$name = $book ->DoctorName;
		echo "姓名:". $name."<hr>";
		
		//2. 性别(Gender)
		$sex = $book ->Gender;
		if($sex == '1'){
			$sex = '男';
		}else{
			$sex = '女';
		}
		echo "性别:".$sex."<hr>";

		//地区
		$area = $book ->OrgArea;
		echo "医师所在地区全称:".$area."<hr>";

		//3. 执业范围分类
		$med = $book ->MedicScope;		
		$medarr = array('101'=>'内科专业','102'=>'外科专业','103'=>'妇产科专业','104'=>'儿科专业','105'=>'眼耳鼻咽喉科专业','106'=>'皮肤病与性病专业','107'=>'精神卫生专业','108'=>'职业病专业','109'=>'医学影像和放射治疗专业','110'=>'医学检验、病理专业','111'=>'全科医学专业','112'=>'急救医学专业','113'=>'康复医学专业','114'=>'预防保健专业','115'=>'特种医学与军事医学专业','116'=>'计划生育技术服务专业','117'=>'重症医学专业','118'=>'肿瘤专业','127'=>'省级以上卫生行政部门规定的其他专业','201'=>'中医专业','202'=>'中西医结合专业','203'=>'蒙医专业','204'=>'藏医专业','205'=>'维医专业','206'=>'傣医专业','207'=>'全科医学专业','208'=>'省级以上卫生行政部门规定的其他专','301'=>'口腔专业','309'=>'口腔麻醉专业','310'=>'口腔病理专业','311'=>'口腔影象专业','312'=>'省级以上卫生行政部门规定的其他专业','401'=>'公共卫生类别专业','405'=>'省级以上卫生行政部门规定的其他专业');
		
		echo "执业范围分类:".$medarr["$med"]."<hr>";
	


		//4. 医师从事专业编码 
		$pro = $book ->Profession;	
		$proarr = array('0101'=>'呼吸内科专业','0102'=>'消化内科专业','0103'=>'神经内科专业','0104'=>'心血管内科专业','0105'=>'血液内科专业','0106'=>'肾病学专业','0107'=>'内分泌专业','0108'=>'免疫学专业','0109'=>'变态反应专业','0110'=>'老年病专业','0111'=>'其他','0201'=>'普通外科专业','0202'=>'神经外科专业','0203'=>'骨科专业','0204'=>'泌尿外科专业','0205'=>'胸外科专业','0206'=>'心脏大血管外科专业','0207'=>'烧伤科专业','0208'=>'整形外科专业','0209'=>'其他','0301'=>'妇科专业','0302'=>'产科专业','0303'=>'计划生育专业','0304'=>'优生学专业','0305'=>'生殖健康与不孕症专业','0306'=>'其他','0401'=>'青春期保健专业','0402'=>'围产期保健专业','0403'=>'更年期保健专业','0404'=>'妇女心理卫生专业','0405'=>'妇女营养专业','0406'=>'其他','0501'=>'新生儿专业','0502'=>'小儿传染病专业','0503'=>'小儿消化专业','0504'=>'小儿呼吸专业','0505'=>'小儿心脏病专业','0506'=>'小儿肾病专业','0507'=>'小儿血液病专业','0508'=>'小儿神经病学专业','0509'=>'小儿内分泌专业','0510'=>'小儿遗传病专业','0511'=>'小儿免疫专业','0512'=>'其他','0601'=>'小儿普通外科专业','0602'=>'小儿骨科专业','0603'=>'小儿泌尿外科专业','0604'=>'小儿胸心外科专业','0605'=>'小儿神经外科专业','0606'=>'其他','0701'=>'儿童生长发育专业','0702'=>'儿童营养专业','0703'=>'儿童心理卫生专业','0704'=>'儿童五官保健专业','0705'=>'儿童康复专业','0706'=>'其他','0901'=>'耳科专业','0902'=>'鼻科专业','0903'=>'咽喉科专业','0904'=>'其他','1001'=>'口腔内科专业','1002'=>'口腔颌面外科专业','1003'=>'正畸专业','1004'=>'口腔修复专业','1005'=>'口腔预防保健专业','1006'=>'其他','1101'=>'皮肤病专业','1102'=>'性传播疾病专业','1103'=>'其他','1401'=>'美容外科专业','1402'=>'美容皮肤科专业','1403'=>'美容中医科专业','1404'=>'美容牙科专业','1501'=>'精神病专业','1502'=>'精神卫生专业','1503'=>'药物依赖专业','1504'=>'精神康复专业','1505'=>'社区防治专业','1506'=>'临床心理专业','1507'=>'司法精神专业','1508'=>'其他','1601'=>'肠道传染病专业','1602'=>'呼吸道传染病专业','1603'=>'肝炎专业','1604'=>'虫媒传染病专业','1605'=>'动物源性传染病专业','1606'=>'蠕虫病专业','1607'=>'其它','2301'=>'职业中毒专业','2302'=>'尘肺专业','2303'=>'放射病专业','2304'=>'物理因素损伤专业','2305'=>'职业健康监护专业','2306'=>'其他','2801'=>'临床体液、血液专业','2802'=>'临床微生物学专业','2803'=>'临床化学检验专业','2804'=>'临床免疫、血清学专业','2805'=>'临床细胞分子遗传学专业','2806'=>'其他','3001'=>'X线诊断专业','3002'=>'CT诊断专业','3003'=>'磁共振成像诊断专业','3004'=>'核医学专业','3005'=>'超声诊断专业','3006'=>'心电诊断专业','3007'=>'脑电及脑血流图诊断专业','3008'=>'神经肌肉电图专业','3009'=>'介入放射学专业','3010'=>'放射治疗专业','3011'=>'其他','3201'=>'内科专业','3202'=>'外科专业','3203'=>'妇产科专业','3204'=>'儿科专业','3205'=>'皮肤科专业','3206'=>'眼科专业','3207'=>'耳鼻咽喉科专业','3208'=>'口腔科专业','3209'=>'肿瘤科专业','3210'=>'骨伤科专业','3211'=>'肛肠科专业','3212'=>'老年病科专业','3213'=>'针灸科专业','3214'=>'推拿科专业','3215'=>'康复医学专业','3216'=>'急诊科专业','3217'=>'预防保健科专业','3218'=>'其他','3301'=>'维吾尔医学','3302'=>'藏医学','3303'=>'蒙医学','3304'=>'彝医学','3305'=>'傣医学','3306'=>'其他',);
		echo "医师从事专业编码 :".$proarr["$pro"]."<hr>";;

		//5.医师所属执业机构名称
		
		$Institution = $book ->Institution;
		echo "医师所属执业机构名称:". $Institution."<hr>";

		//6.医师执业证书编码

		$Lisence = $book ->Lisence;
		echo "医师执业证书编码:". $Lisence."<br><br>";

		$SerialCode = $book ->SerialCode;
		echo "编码:". $SerialCode."<br><br>";
	}
	
	}else{
		echo "错误";
	}


?>