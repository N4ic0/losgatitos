$FTP_HOST = "108.167.149.249"
$FTP_USER = "naicolg_gatito"
$FTP_PASS = "brXieZ&IJmc$"

Write-Host "=== Deploy a Produccion (Local) ===" -ForegroundColor Magenta

Write-Host "[1/3] Sincronizando cambios a Docker..." -ForegroundColor Cyan
docker run --rm -v "D:\Desarrollo\Contenedores\losgatitos\src:/source" -v losgatitos_src:/target alpine sh -c "cp -a /source/. /target/"

Write-Host "[2/3] Construyendo assets en Docker..." -ForegroundColor Cyan
docker exec laravel_app npm run build

Write-Host "[3/3] Subiendo archivos via FTP..." -ForegroundColor Cyan
docker run --rm -v losgatitos_src:/src alpine sh -c "
  apk add --no-cache lftp >/dev/null 2>&1
  cd /src
  lftp -c \"
    set ftp:passive-mode off
    set ftp:ssl-allow no
    set net:timeout 120
    set mirror:parallel-transfer-count 10
    set mirror:use-pget-n 10
    open -u '$FTP_USER','$FTP_PASS' $FTP_HOST
    mirror -R \\
      --exclude .env \\
      --exclude .env.example \\
      --exclude .gitignore \\
      --exclude node_modules/ \\
      --exclude tests/ \\
      --exclude docker-compose.yml \\
      --exclude docker-compose.opencode.yml \\
      --exclude Dockerfile \\
      --exclude phpunit.xml \\
      --exclude README.md \\
      --delete \\
      . /
    quit
  \"
"

if ($LASTEXITCODE -eq 0) {
    Write-Host "=== DEPLOY COMPLETADO ===" -ForegroundColor Green
} else {
    Write-Host "=== ERROR en el deploy ===" -ForegroundColor Red
}
