<?php
namespace App\Services;

class RUTService
{
    public static function validar(string $rut): bool
    {
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        if (strlen($rut) < 2) return false;
        
        $dv = strtoupper(substr($rut, -1));
        $numero = substr($rut, 0, -1);
        
        if (!is_numeric($numero)) return false;
        
        $suma = 0;
        $multiplo = 2;
        
        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $suma += intval($numero[$i]) * $multiplo;
            $multiplo = $multiplo < 7 ? $multiplo + 1 : 2;
        }
        
        $resto = $suma % 11;
        $dvCalculado = 11 - $resto;
        
        if ($dvCalculado == 11) $dvCalculado = '0';
        if ($dvCalculado == 10) $dvCalculado = 'K';
        
        return (string) $dvCalculado === $dv;
    }

    public static function formatear(string $rut): string
    {
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        $dv = strtoupper(substr($rut, -1));
        $numero = substr($rut, 0, -1);
        $numero = number_format($numero, 0, '', '.');
        return $numero . '-' . $dv;
    }

    public static function limpiar(string $rut): string
    {
        return preg_replace('/[^k0-9]/i', '', $rut);
    }
}
