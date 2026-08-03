$ErrorActionPreference = 'Stop'
$sessionFile = Join-Path $env:TEMP 'lg_c5.txt'
Remove-Item $sessionFile -ErrorAction SilentlyContinue
$loginHtml = curl.exe -s -c $sessionFile 'http://localhost:8080/login'
$m = [regex]::Match($loginHtml, 'name="_token"\s+value="([^"]+)"')
$token = $m.Groups[1].Value
curl.exe -s -b $sessionFile -c $sessionFile -d "_token=$token&email=admin@motellosgatitos.cl&password=probatemp" 'http://localhost:8080/login' -w "`nLOGIN_HTTP=%{http_code}`n" | Select-String "LOGIN_HTTP"
$dash = curl.exe -s -b $sessionFile -c $sessionFile 'http://localhost:8080/admin'
$m2 = [regex]::Match($dash, 'name="csrf-token"\s+content="([^"]+)"')
$csrf = $m2.Groups[1].Value
Write-Output "CSRF=$csrf"
$resp = curl.exe -s -b $sessionFile -H 'Content-Type: application/json' -H 'Accept: application/json' -H "X-CSRF-TOKEN: $csrf" -d '{"aire":true}' -w "`nHTTP=%{http_code}`nREDIRECT=%{redirect_url}" 'http://localhost:8080/admin/dashboard/habitacion/1/aire'
Write-Output "RESP=$resp"