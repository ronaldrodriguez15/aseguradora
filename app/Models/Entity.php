<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nemo',
        'cnitpagador',
        'sucursal',
        'n-apertura',
        'fecha-apertura',
        't-empresa',
        'direccion',
        'department',
        'ciudad_expedicion',
        'pbx',
        'n-empleados',
        'n-contratistas',
        'p-salario',
        'n-sedes',
        't-orden-empresa',
        'arl-empresa',
        'm-hibrido',
        'd-asistencia',
        'd-numero-personas',
        'v-salario-minimo',
        'v-salario-maximo',
        'pago-nomina',
        'm-socializacion',
        'tthh-nombres',
        'tthh-cel1',
        'tthh-cel2',
        'tthh-cel3',
        'tthh-email',
        'tthh-cargo',
        'tthh-observaciones',
        'area-nomina-nombres',
        'area-nomina-celular',
        'area-nomina-observaciones',
        'observaciones-visado',
        'archivos-radicacion',
        'ea-nombres',
        'ea-cel',
        'ea-email',
        'ea-cargo',
        'ea-observaciones',
        'at-cargo',
        'at-observaciones',
        'observaciones-c',
        'codigo',
        '1-1',
        'ruta',
        'zona',
        'codigo-postal',
        'afiliados-planta',
        'afiliados-contratistas',
        'historial-afiliados',
        'apertura',
        'c-codigo',
        'status'
    ];
}
