<?php
use Illuminate\Support\Facades\DB;
$sql = "ALTER TABLE promocion_producto ADD COLUMN valor_promocion INT NULL AFTER cantidad";
DB::statement($sql);
echo "OK\n";
