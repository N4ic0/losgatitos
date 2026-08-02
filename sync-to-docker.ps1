# Sincroniza cambios del código fuente desde Windows al volumen Docker
# Ejecuta: .\sync-to-docker.ps1 (desde PowerShell en D:\Desarrollo\Contenedores\losgatitos)

Write-Host "Sincronizando src/ → losgatitos_src (sin node_modules/vendor/build/storage)..." -ForegroundColor Cyan
docker run --rm -v "D:\Desarrollo\Contenedores\losgatitos\src:/source" -v losgatitos_src:/target alpine sh -c "tar cf - --exclude=node_modules --exclude=vendor --exclude=public/build --exclude=storage --exclude=.git -C /source . | tar xf - -C /target"

if (-not $?) {
    Write-Host "❌ Error en sincronización" -ForegroundColor Red
    exit 1
}

Write-Host "Limpiando caches de Laravel..." -ForegroundColor Cyan
docker exec laravel_app php artisan optimize:clear 2>&1 | Out-Null
docker exec laravel_app php artisan route:clear 2>&1 | Out-Null
docker exec laravel_app php artisan view:clear 2>&1 | Out-Null
docker exec laravel_app php artisan config:clear 2>&1 | Out-Null
docker exec laravel_app php artisan cache:clear 2>&1 | Out-Null

Write-Host "✅ Sincronización y limpieza completa" -ForegroundColor Green
