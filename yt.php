<?php
$line_length = 50;
$bg_red = "\e[41m";$white = "\e[97m";
$res = "\e[0m";$red = "\e[91m";
$end = "\e[0m";$green = "\e[92m";  
$res="\033[0m";$g="\033[1;30m";
$w="\033[1;37m";$r="\033[1;31m";
$g="\033[1;32m";$y="\033[1;33m";
$p="\033[1;35m";$l="\033[1;36m";$try = 1;
system("clear");error_reporting(0);
define("line",$g.str_repeat("━",60)."\n");



$cookie = save("cookie");
$user_agent = save("user_agent");
system("clear");error_reporting(0);
banner("YouLikeHits");
$headers = [
"accept: application/json,text/javascript, */*; q=0.01",
"x-requested-with:XMLHttpRequest",
"user-agent: ".$user_agent,
"content-type: application/x-www-form-urlencoded; charset=UTF-8",
"cookie: ".$cookie
];

$header = [
"content-type: application/x-www-form-urlencoded; charset=UTF-8",
"accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
"user-agent: ".$user_agent,
"cookie: ".$cookie
];


dashboard:
$link = "https://www.youlikehits.com/earnpoints.php";
$data = new DOMDocument();
$htmlContent = get($link, $header);
if($htmlContent !== false){
 @$data->loadHTML($htmlContent);
 $userDiv = $data->getElementById('userDiv');
 if($userDiv) {
  $usernameSpan = $userDiv->getElementsByTagName('span')->item(0);
  if($usernameSpan) {
   $username = $usernameSpan->textContent;
   echo $w."Username {$r}: $g".trim($username)."\n";
  }
  else {
   echo "Username not found.";
  }
 }
 $pointsDiv = $data->getElementById('pointsinfoDiv');
 if($pointsDiv) {
 $pointsSpan = $pointsDiv->getElementsByTagName('span')->item(0);
  if($pointsSpan) {
   $points = $pointsSpan->textContent;
   echo $w."Points   {$r}: {$g}".trim($points)."\n";
  }
 }
 echo line;
}
else{ goto dashboard; }



youtubeView:

$link = "https://www.youlikehits.com/youtubenew2.php";
$data = new DOMDocument();
$htmlContent = get($link, $header);
if($htmlContent !== false) {
 @$data->loadHTML($htmlContent);
 $anchors = $data->getElementsByTagName('a');
 foreach($anchors as $anchor) {
  $onclick = $anchor->getAttribute('onclick');
  if(strpos($onclick, 'imageWin') !== false) {
   preg_match('/imageWin\(([^)]+)\);/', $onclick, $matches);   
   if(isset($matches[1])){
    $value = explode(',',str_replace("'",'',$matches[1]));
    $siteId[$value[0]] = $value; 
   }
  }
 }
}
else{ goto youtubeView; }

foreach($siteId as $key){
 $link = "https://www.youlikehits.com/youtuberender.php?id=$value[0]";
 $data = get($link, $header);  
 timer($value[2],"Wait For"); 
 $randomValue = mt_rand(0, 999999) / 100000000;
 $rand = number_format($randomValue, 17);
 $link = "https://www.youlikehits.com/playyoutubenew.php?id=$key[0]&step=points&x=$key[3]&rand={$rand}";
  $data = get($link, $header);
  $reward = @getStr($data,'<font color=red>',' Points Added!</font>');
 if($reward){
  $points = getStr($data,'document.getElementById("currentpoints").innerHTML="','";');
  echo $w."Type   {$r}:$g Youtube View\n";
  echo $w."Id     {$r}: $g$key[0]\n";
  echo $w."Reward {$r}:$g $reward\n";
  echo $w."Points {$r}:$g $points\n";
  echo line;
 }
}
if($siteId){
 unset($siteId);
 goto youtubeView;
}
echo $w."There are no YouTube Video currently viewable for Points\n";
echo line;
timer(5*60,"Wait For");
goto youtubeView;










function timer($x,$text){
$dot = 1;
$res="\033[0m";$g="\033[1;30m";
$w="\033[1;37m";$r="\033[1;31m";
$g="\033[1;32m";$y="\033[1;33m";
$p="\033[1;35m";$l="\033[1;36m";
for($x=$x; $x > -1;$x--){
$colors = array(
"g" => "\033[1;30m","w" => "\033[1;37m",
"r" => "\033[1;31m","g" => "\033[1;32m",
"y" => "\033[1;33m","p" => "\033[1;35m",
"l" => "\033[1;36m");
$color = array_rand($colors);
$color = $colors[$color];
$high = str_repeat ("".$color."•",$dot);
if($x <= 59){
echo " ".$w."$text $x ".$l."".$high."";}
if($x >= 60){
if($x <= 3599){
echo " ".$w."$text ".gmdate("i:s",$x)." ".$l."".$high."";}}
if($x >= 3600){
echo  " ".$w."$text ".gmdate("h:i:s",$x)." ".$l."".$high."";}
if($dot == 5){$dot = 1;}
else{$dot++;}
sleep(1);
echo "\r".str_repeat(" ",70)."\r";


}}

function remove($filename){
$file = file_get_contents("config.json");
$file = json_decode($file,true);
unset($file[$filename]);
$data = json_encode($file,JSON_PRETTY_PRINT);
file_put_contents("config.json",$data);
}

function save($filename){
$res="\033[0m";$g="\033[1;30m";
$w="\033[1;37m";$r="\033[1;31m";
$g="\033[1;32m";$y="\033[1;33m";
$p="\033[1;35m";$l="\033[1;36m";
$file = file_get_contents("config.json");
$file = json_decode($file,true);
if($file[$filename]){
 return(strval("$file[$filename]"));
}

if(!$file){
 $file = [];
}

$data = readline($w."Input ".$l.$filename.$w." : ");
$data = array_merge($file,[$filename => $data]);
$data = json_encode($data,JSON_PRETTY_PRINT);
file_put_contents("config.json",$data);

$file = file_get_contents("config.json");
$file = json_decode($file,true);
return(strval("$file[$filename]"));
}


function silent_save($filename,$data){
$res="\033[0m";$g="\033[1;30m";
$w="\033[1;37m";$r="\033[1;31m";
$g="\033[1;32m";$y="\033[1;33m";
$p="\033[1;35m";$l="\033[1;36m";
$file = file_get_contents("config.json");
$file = json_decode($file,true);
if($file[$filename]){
 return(strval("$file[$filename]"));
}

if(!$file){ $file = []; }

$data = array_merge($file,[$filename => $data]);
$data = json_encode($data,JSON_PRETTY_PRINT);
file_put_contents("config.json",$data);

$file = file_get_contents("config.json");
$file = json_decode($file,true);
return(strval("$file[$filename]"));
}


function getStr($string, $start, $end,$num = 1){
  $str = explode($start, $string);
  $str = explode($end, $str[$num]);
  return $str[0];
}


function get($link,$header){
$ch = curl_init();
curl_setopt_array($ch, [
CURLOPT_URL => $link,
CURLOPT_FOLLOWLOCATION => 1,
CURLOPT_RETURNTRANSFER => 1,
CURLOPT_HTTPHEADER => $header,
CURLOPT_COOKIEJAR => getcwd() ."/cookie.txt",
CURLOPT_COOKIEFILE => getcwd() ."/cookie.txt",
CURLOPT_SSL_VERIFYPEER => 0
]);
$data = curl_exec($ch);
curl_close($ch);
return($data);
}

function post($link,$header,$request){
$ch = curl_init();
curl_setopt_array($ch, [
CURLOPT_URL => $link,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_COOKIEJAR => getcwd() ."/cookie.txt",
CURLOPT_COOKIEFILE => getcwd() ."/cookie.txt",
CURLOPT_POST => true,
CURLOPT_POSTFIELDS => $request,
CURLOPT_HTTPHEADER => $header,
]);
$post = curl_exec($ch);
curl_close($ch);
return($post);
}
function banner($name){
$r1="\033[01;38;5;196m";$r2="\033[01;38;5;202m";
$r3="\033[01;38;5;208m";$ry="\033[01;38;5;214m";
$y1="\033[01;38;5;220m";$y2="\033[01;38;5;226m";
$y3="\033[01;38;5;228m";$g="\033[1;32m";

$sh = shell_exec("curl http://ip-api.com/json/ 2>/dev/null");
$sh = @json_decode($sh,true);
$ip = @$sh["query"];
$time = @$sh["timezone"];
$time = @date_default_timezone_set($time);
$time = date("h:i:s A");
$date = date("M d D");
$msg = "
$r1 ██╗    ██╗".$r2."███".$r3."███╗ ██╗██████".$ry."██╗███████".$y1."╗██████╗  Status online
$r1 ██║    ██║█".$r2."█╔══██╗".$r3."██║╚══██╔══╝█".$ry."█╔════╝██".$y1."╔══██╗ Script By: Writer
$r1 ██║ █╗ ██║███".$r2."███╔╝██║".$r3."   ██║   ████".$ry."█╗  █████".$y1."█╔╝ Script Name: $name
$r1 ██║███╗██║██╔══█".$r2."█╗██║   █".$r3."█║   ██╔══╝  ".$ry."██╔══██╗ Script Not for sale
$r1 ╚███╔███╔╝██║  ██║".$r2."██║   ██║ ".$r3."  ███████╗██║ ".$ry." ██║ T.G. Channel @writer_script
$r1  ╚══╝╚══╝ ╚═╝  ╚═╝╚═".$r2."╝   ╚═╝   ╚".$r3."══════╝╚═╝  ╚═╝ T.G. Group @writer_scripts
$r1 Use The Script At Your".$r2." Own Risk
$g Ip : $ip Date: $date Time [ $time ]";

slowmsg($msg,700);
echo "\n";
}
function slowmsg($msg,$usleep){
$slow = str_split($msg);
foreach($slow as $i){
echo $i;
usleep($usleep);
}
echo "\n";

}
?>