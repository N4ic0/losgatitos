$ErrorActionPreference = 'Stop'
$sessionFile = Join-Path $env:TEMP 'lg_c2.txt'
Remove-Item $sessionFile -ErrorAction SilentlyContinue
$loginHtml = curl.exe -s -c $sessionFile 'http://localhost:8080/login'
$match = [regex]::Match($loginHtml, 'name="_token"\s+value="([^"]+)"')
if (-not $match.Success) { Write-Output 'NO_TOKEN'; exit 1 }
$token = $match.Groups[1].Value
curl.exe -s -b $sessionFile -c $sessionFile -d "_token=$token&email=admin@motellosgatitos.cl&password=probatemp" -L 'http://localhost:8080/login' | Out-Null
# toggle hab room 1 a true via POST aire
$resp = curl.exe -s -b $sessionFile -H 'Content-Type: application/json' -H 'Accept: application/json' -H "X-CSRF-TOKEN: $token" -d '{"aire":true}' 'http://localhost:8080/admin/dashboard/habitacion/1/aire'
Write-Output "RESP1=$resp"
$resp2 = curl.exe -s -b $sessionFile -H 'Content-Type: application/json' -H 'Accept: application/json' -H "X-CSRF-TOKEN: $token" -d '{"aire":false}' 'http://localhost:8080/admin/dashboard/habitacion/1/aire'
Write-Output "RESP2=$resp2"