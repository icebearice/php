<?php
$arr = [
   0=>[
	   'id'=>1,
	   'name'=>'ces'
   ],
   1=>[
	   'id'=>2,
	   'name'=>'ces1'
   ],

];

$arr1 = [
	0=>[
		'username' =>'***11',
		'cost' => '***12'
	]
];

$num = 10001;
$last_num = substr($num,$length-1);
echo $last_num;die;

$str = json_encode($arr1);
$str_arr = json_decode($str,true);
print_r($str_arr);die;


$cost = sprintf("%03d",1);
$cost_len=strlen($cost);
$cost = substr($cost,0,$cost_len-2).'.'.substr($cost,$cost_len-2,2);
echo $cost;
die;
print_r(array_column($arr,'id'));die;
