<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Jetstream\HasProfilePhoto;

class User extends Authenticatable
{
    use HasProfilePhoto;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'name_entity',
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
        'vendedores_id',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];
}
