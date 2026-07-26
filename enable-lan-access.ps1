$wslIp = (wsl -d Ubuntu -- bash -c "hostname -I").Trim().Split(" ")[0]
Write-Host "WSL2 IP: $wslIp"

$ports = @(8080, 3308)

foreach ($port in $ports) {
    netsh interface portproxy delete v4tov4 listenport=$port listenaddress=0.0.0.0
    netsh interface portproxy add v4tov4 listenport=$port listenaddress=0.0.0.0 connectport=$port connectaddress=$wslIp
    Write-Host "Port $port forwarded: 0.0.0.0:$port -> $wslIp`:$port"
}

netsh advfirewall firewall add rule name="Laravel 8080 LAN" dir=in action=allow protocol=TCP localport=8080

Write-Host "LAN access enabled. Verify with: curl.exe http://192.168.100.142:8080"
