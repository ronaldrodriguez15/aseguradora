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
        'n_apertura',
        'fecha_apertura',
        't_empresa',
        'direccion',
        'ambiente',
        'empresas',
        'department',
        'ciudad_expedicion',
        'pbx',
        'n_empleados',
        'n_contratistas',
        'p_salario',
        'n_sedes',
        't_orden_empresa',
        'arl_empresa',
        'm_hibrido',
        'd_asistencia',
        'd_numero_personas',
        'v_salario_minimo',
        'v_salario_maximo',
        'pago_nomina',
        'm_socializacion',
        'tthh_nombres',
        'tthh_cel1',
        'tthh_cel2',
        'tthh_cel3',
        'tthh_email',
        'tthh_cargo',
        'tthh_observaciones',
        'area_nomina_nombres',
        'area_nomina_celular',
        'area_nomina_email',
        'area_nomina_cargo',
        'area_nomina_observaciones',
        'observaciones_visado',
        'archivos_radicacion',
        'ea_nombres',
        'ea_cel',
        'ea_email',
        'ea_cargo',
        'ea_observaciones',
        'at_nombres',
        'at_cel',
        'at_email',
        'at_cargo',
        'at_observaciones',
        'observaciones_c',
        'codigo',
        '1_1',
        'ruta',
        'zona',
        'codigo_postal',
        'afiliados_planta',
        'afiliados_contratistas',
        'historial_afiliados',
        'apertura',
        'c_codigo',
        'status'
    ];
}
