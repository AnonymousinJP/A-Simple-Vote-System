<?php session_start();
$access="./.htaccess";/*アップロード用htaccessファイル*/
//$access="./htaccess";
//$Ип=/*""*/;
$myip="121.XX.XX.XXX";
$files=array(".htaccess","vote.txt");
$f_name="./vote.txt";
foreach($files as $value) {
  if(!file_exists($value)){
    //touch($value);file_put_contents($access,"<Limit GET POST>\n</Limit>");/*ファイル＆ディレクティブ作成,この部分を変更*/
    touch($value);file_put_contents($access,"<Files vote.php>\n</Files>");
    echo "files doesn't exist<br>";
  }
  else{echo "file exists<br>";}
}

function access(){
  global $access;
  $accCont=file($access);
  //$ipAddr="Require not ip ".$_SERVER['REMOTE_ADDR']."\n";/*この部分をdenyfromに変更*/
  $ipAddr="deny from ".$_SERVER['REMOTE_ADDR']."\n";
  //$htWd="<Limit GET POST>";/*この部分を変更*/
  $htWd="<Files vote.php>";
  $accCont=str_replace($htWd,$htWd."\n".$ipAddr,$accCont);/**/
  file_put_contents($access,$accCont);
}
function vote(){
  global $f_name;
  for($i=0;$i<count($_POST["btn"]);$i++){
    echo $_POST["btn"][$i];/*radioの値を取得*/
    $f_content=$_POST["btn"][$i];
    file_put_contents($f_name,$f_content,FILE_APPEND);
  }
}

if($_SERVER['REMOTE_ADDR']==$myip/*$мойИп*/){echo "your IP is Permitted<br>";}
else{/*ipアドレスを記録する場合*/
  if((isset($_POST["btn"])==true)&&(isset($_POST["submit"])==true)){
    if((isset($_REQUEST["token"])==true)&&(isset($_SESSION["token"])==true)&&($_REQUEST["token"]==$_SESSION["token"])){
        if(!empty($_POST["btn"])){
          access();
          vote();
        }else{echo "false";}
    }
  }
}

$result0=substr_count(file_get_contents($f_name),"val0");
$result1=substr_count(file_get_contents($f_name),"val1");
$result2=substr_count(file_get_contents($f_name),"val2");
$result3=substr_count(file_get_contents($f_name),"val3");
$result4=substr_count(file_get_contents($f_name),"val4");
$total=$result0+$result1+$result2+$result3+$result4;
echo "<br>count:",$result0,$result1,$result2,$result3,$result4,"<br>";
echo $result0/$total*100,"+",$result1/$total*100,"+",$result2/$total*100,"+",$result3/$total*100,"+",$result4/$total*100,"%<br>";
echo file_get_contents($f_name),"<br>";

$_SESSION["token"]=$token=mt_rand();
?>
<?php echo $access;?>

<!DOCTYPE html>
<html>
<head><meta charset="utf-8" name="viewport" content="width=device-width,initial-scale=1">
  <style media="screen">
    #canvas0,#canvas1,#canvas2,#canvas3,#canvas4{background:red;}
  </style>
  <title>vote</title>
</head>
<body>
  <form class="" action="vote.php" method="post">
    <input type="hidden" name="token" value="<?php echo $token;-1010?>">
    <p>０<input type="radio" id="result0" name="btn[]" value="val0">
      <canvas id="canvas0" width="<?=$result0/$total*200?>px" height="10"></canvas>
    </p>
    <p>１<input type="radio" id="result1" name="btn[]" value="val1">
      <canvas id="canvas1" width="<?=$result1/$total*200?>px" height="10"></canvas>
    </p>
    <p>２<input type="radio" id="result2" name="btn[]" value="val2">
      <canvas id="canvas2" width="<?=$result2/$total*200?>px" height="10"></canvas>
    </p>
    <p>３<input type="radio" id="result3" name="btn[]" value="val3">
      <canvas id="canvas3" width="<?=$result3/$total*200?>px" height="10"></canvas>
    </p>
    <p>４<input type="radio" id="result4" name="btn[]" value="val4">
      <canvas id="canvas4" width="<?=$result4/$total*200?>px" height="10"></canvas>
    </p>
    <h3><?=$total,"票"?></h3>
    <input type="submit" name="submit" value="submit">
    <h1><?=$_SERVER['REMOTE_ADDR']?></h1>
  </form>
<div id="memo"></div>
<div id="memo0"></div>
<div id="memo1"></div>
<script>

let files=function(files){
  document.getElementById("memo1").innerHTML=files;
}
files("test");

let val="<?=$value?>";
document.getElementById("memo0").innerHTML=val;

let memo="\
・０アップロード時は$accessでhtaccessを.htaccessファイルにする<br>\
・１sessionによるアクセス制限<br>\
・２自分のipアドレスをhtaccessファイルに書き込まないようにする<br>\
・３自分のipアドレスの時はinputを選択できないようにする<br>\
・４ipアドレス処理を関数化<br>\
・５inputで取得する値を送信の段階で1,2,3,4,5に分けて、テキストファイルに格納された数字の個数をcanvasと結果に反映する<br>\
・６アクセス制限されたユーザーが結果を閲覧できるようにする→別のページにも結果を反映<br>\
・７繰り返し処理で項目を追加できるようにしたい\
\
";
document.getElementById("memo").innerHTML=memo;
</script>
</body>
</html>
