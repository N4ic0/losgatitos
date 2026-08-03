$ErrorActionPreference = 'Stop'
$sessionFile = Join-Path $env:TEMP 'lg_c3.txt'
Remove-Item $sessionFile -ErrorAction SilentlyContinue
$loginHtml = curl.exe -s -c $sessionFile 'http://localhost:8080/login'
$m = [regex]::Match($loginHtml, 'name="_token"\s+value="([^"]+)"')
$token = $m.Groups[1].Value
curl.exe -s -b $sessionFile -c $sessionFile -d "_token=$token&email=admin@motellosgatitos.cl&password=probatemp" -L 'http://localhost:8080/login' | Out-Null
# obtener token del dashboard (el nuevo csrf meta)
$dash = curl.exe -s -b $sessionFile -c $sessionFile 'http://localhost:8080/admin'
$m2 = [regex]::Match($dash, 'name="csrf-token"\s+content="([^"]+)"')
if (-not $m2.Success) { Write-Output 'NO_CSRF_META_IN_DASH'; exit 1 }
$csrf = $m2.Groups[1].Value
Write-Output "CSRF_META=$csrf"
$resp = curl.exe -s -b $sessionFile -H 'Content-Type: application/json' -H 'Accept: application/json' -H "X-CSRF-TOKEN: $csrf" -d '{"aire":true}' 'http://localhost:8080/admin/dashboard/habitacion/1/aire'
Write-Output "RESP=$resp"