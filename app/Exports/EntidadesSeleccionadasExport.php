<?php

namespace App\Exports;

use App\Models\Entity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EntidadesSeleccionadasExport implements FromCollection, WithHeadings, WithMapping
{
    protected $ids;

    // Atributos reales
    protected $attributes = [
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
    ];

    // Atributos personalizados
    protected $headings = [
        'Nombre de la entidad',
        'Abreviatura',
        'NIT o Código',
        'Sucursal',
        'Número apertura',
        'Fecha apertura',
        'Tipo de empresa',
        'Dirección',
        'Departamento',
        'Ciudad',
        'PBX',
        'Número de empleados de planta',
        'Número de empleados contratista',
        'Número de personas que ganan el salario mínimo',
        'Número de personas que ganan el salario máximo',
        'Número de sedes',
        'Tipo de orden empresa',
        'ARL de la empresa',
        'Modalidad híbrida',
        'Dínamica de asistencia',
        'Día de la semana donde hay mayor número de personas en la entidad',
        'Valor mínimo de la entidad',
        'Valor del salario maximo en la entidad',
        'Pago de nómina',
        'Metodología de socialización beneficio',
        'Director de TTHH - Nombres y apellidos',
        'Director de TTHH - Celular 1',
        'Director de TTHH - Celular 2',
        'Director de TTHH - Celular 3',
        'Director de TTHH - Nombres y apellidos',
        'Director de TTHH - Email',
        'Director de TTHH - Cargo',
        'Director de TTHH - Observaciones',
        'Encargado del área de nómina - Nombres y apellidos',
        'Encargado del área de nómina - Celular 1',
        'Encargado del área de nómina - Email',
        'Encargado del área de nómina - Cargo',
        'Encargado del área de nómina - Observaciones',
        'Observaciones/Proceso de visado/Radicado',
        'Archivos para radicación',
        'Encargado del área de bienestar - Nombres y apellidos',
        'Encargado del área de bienestar - Celular',
        'Encargado del área de bienestar - Email',
        'Encargado del área de bienestar - Cargo',
        'Encargado del área de bienestar - Observaciones',
        'Encargado del área de tesoreria - Nombre y apellido',
        'Encargado del área de tesoreria - Celular',
        'Encargado del área de tesoreria - Email',
        'Encargado del área de tesoreria - Cargo',
        'Encargado del área de tesoreria - Observaciones',
        'Autorizan código',
        'Permiten 1 a 1',
        'Ruta',
        'Zona',
        'Código postal',
        'Afiliados planta',
        'Afiliados contratistas',
        'Historial de afiliados',
        'Con código',
        'Apertura',
        'Observaciones - otros contactos'
    ];

    public function __construct($ids)
    {
        $this->ids = json_decode($ids, true);
    }

    public function collection()
    {
        return Entity::whereIn('id', $this->ids)->get();
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($entity): array
    {
        $row = [];

        foreach ($this->attributes as $attribute) {
            if ($attribute === 'status') {
                $row[] = $entity->status ? 'Activo' : 'Inactivo';
            } else {
                $row[] = $entity->{$attribute};
            }
        }

        return $row;
    }
}

