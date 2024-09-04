<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inability', function (Blueprint $table) {
            $table->id();

            //STEP 1
            $table->unsignedInteger('insurer_id');
            $table->string('no_poliza');
            $table->string('fecha_diligenciamiento');
            $table->string('no_solicitud');
            $table->string('codigo_asesor');
            $table->string('nombre_asesor');
            $table->string('nombre_eps');
            $table->string('fecha_nacimiento_asesor');
            $table->string('email_corporativo');
            $table->string('descuento_eps');
            $table->string('numero_dias');
            $table->string('valor_ibc_basico');
            $table->string('valor_adicional');
            $table->string('total');
            $table->string('amparo_basico');
            $table->string('val_prevexequial_eclusivo');
            $table->string('forma_pago');

            //INFORMACION DEBITO AUTOMATICO STEP 2
            $table->string('tipo_cuenta')->nullable();
            $table->string('no_cuenta')->nullable();
            $table->string('bank_id')->nullable();
            $table->string('ciudad_banco')->nullable();
            $table->string('gastos_administrativos')->nullable();
            $table->string('edad_funcionario')->nullable();
            $table->string('val_total_desc_mensual')->nullable();

            //DATOS DEL ASEGURADO STEP 3
            $table->string('primer_apellido')->nullable();
            $table->string('segundo_apellido')->nullable();
            $table->string('nombres_completos')->nullable();
            $table->string('tipo_identificaciòn')->nullable();
            $table->string('no_identificaciòn')->nullable();
            $table->string('ciudad_expedicion')->nullable();
            $table->string('genero')->nullable();
            $table->string('fecha_nacimiento_asegurado')->nullable();
            $table->string('direccion_residencia')->nullable();
            $table->string('telefono_fijo')->nullable();
            $table->string('celular')->nullable();
            $table->string('ciudad_residencia')->nullable();
            $table->string('fuente_recursos')->nullable();
            $table->string('ocupacion_asegurado')->nullable();
            $table->string('eps_asegurado')->nullable();
            $table->string('entidad_pagadora_sucursal')->nullable();

            //BENEFICIARIOS DEL SOLICITANTE STEP 4
            $table->string('nombres_s1')->nullable();
            $table->string('apellidos_s1')->nullable();
            $table->string('genero_s1')->nullable();
            $table->string('parentesco_s1')->nullable();
            $table->string('edad_s1')->nullable();
            $table->string('porcentaje_s1')->nullable();
            $table->string('tipo_identidad_s1')->nullable();
            $table->string('nombres_s2')->nullable();
            $table->string('apellidos_s2')->nullable();
            $table->string('genero_s2')->nullable();
            $table->string('parentesco_s2')->nullable();
            $table->string('edad_s2')->nullable();
            $table->string('porcentaje_s2')->nullable();
            $table->string('tipo_identidad_s2')->nullable();
            $table->string('nombres_s3')->nullable();
            $table->string('apellidos_s3')->nullable();
            $table->string('genero_s3')->nullable();
            $table->string('parentesco_s3')->nullable();
            $table->string('edad_s3')->nullable();
            $table->string('porcentaje_s3')->nullable();
            $table->string('tipo_identidad_s3')->nullable();

            //PRODUCTOS DEL FUNCIONARIO STEP 5
            $table->string('servicios_prevision_exequial')->nullable();
            $table->string('beneficiario_diario_inc_temp')->nullable();
            $table->string('serv_prevision_exequial_mascotas')->nullable();
            $table->string('serv_prevision_salud')->nullable();
            $table->string('otro')->nullable();
            $table->string('cual')->nullable();
 
            //DECLARACION DE ASEGURABILIDAD STEP 6
            $table->string('cancer')->nullable();
            $table->string('corazon')->nullable();
            $table->string('diabetes')->nullable();
            $table->string('enf_hepaticas')->nullable();
            $table->string('enf_neurologicas')->nullable();
            $table->string('pulmones')->nullable();
            $table->string('presion_arterial')->nullable();
            $table->string('rinones')->nullable();
            $table->string('infeccion_vih')->nullable();
            $table->string('perdida_funcional_anatomica')->nullable();
            $table->string('accidentes_labores_ocupacion')->nullable();
            $table->string('hospitalizacion_intervencion_quirurgica')->nullable();
            $table->string('enfermedad_diferente')->nullable();
            $table->string('enf_cerebrovasculares')->nullable();
            $table->string('cirugias')->nullable();
            $table->string('alcoholismo')->nullable();
            $table->string('tabaquismo')->nullable();
            $table->string('enf_congenitas')->nullable();
            $table->string('enf_colageno')->nullable();
            $table->string('enf_hematologicas')->nullable();
            $table->string('descripcion_de_enfermedades')->nullable();

            //REFERENCIAS LABORALES STEP 7
            $table->string('nombres_apellidos_r1')->nullable();
            $table->string('telefono_r1')->nullable();
            $table->string('entidad_r1')->nullable();
            $table->string('nombres_apellidos_r2')->nullable();
            $table->string('telefono_r2')->nullable();
            $table->string('entidad_r2')->nullable();
            $table->string('nombres_apellidos_r3')->nullable();
            $table->string('telefono_r3')->nullable();
            $table->string('entidad_r3')->nullable();

            $table->string('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inability');
    }
}
