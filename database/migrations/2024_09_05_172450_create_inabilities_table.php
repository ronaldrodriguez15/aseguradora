<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inabilities', function (Blueprint $table) {
            $table->id();

            //STEP 1
            $table->unsignedInteger('insurer_id');
            $table->string('no_poliza', 18);
            $table->string('aseguradora', 10);
            $table->date('fecha_diligenciamiento');
            $table->string('no_solicitud', 12);
            $table->string('codigo_asesor', 30);
            $table->string('nombre_asesor', 50); // Reducido
            $table->string('nombre_eps', 50); // Reducido
            $table->date('fecha_nacimiento_asesor');
            $table->string('email_corporativo')->unique();
            $table->string('descuento_eps', 20);
            $table->integer('numero_dias');
            $table->integer('edad');
            $table->string('tu_pierdes', 5);
            $table->decimal('valor_ibc_basico', 20, 2); // Cambiado a decimal
            $table->string('valor_adicional', 10);
            $table->string('total', 50);
            $table->string('amparo_basico', 30);
            $table->string('val_prevexequial_eclusivo', 30);
            $table->string('forma_pago', 30);

            //INFORMACION DEBITO AUTOMATICO STEP 2
            $table->string('tipo_cuenta', 30)->nullable();
            $table->string('no_cuenta', 40)->nullable();
            $table->unsignedInteger('bank_id')->nullable();
            $table->string('banco', 50)->nullable();
            $table->string('ciudad_banco', 50)->nullable();
            $table->string('gastos_administrativos', 30)->nullable();
            $table->unsignedInteger('edad_funcionario')->nullable();
            $table->decimal('val_total_desc_mensual', 20, 2)->nullable();

            //DATOS DEL ASEGURADO STEP 3
            $table->string('primer_apellido', 70)->nullable();
            $table->string('segundo_apellido', 70)->nullable();
            $table->string('nombres_completos', 70)->nullable();
            $table->string('tipo_identificacion', 70)->nullable();
            $table->string('no_identificacion', 16)->nullable();
            $table->string('ciudad_expedicion', 60)->nullable();
            $table->string('genero', 15)->nullable();
            $table->date('fecha_nacimiento_asegurado')->nullable();
            $table->string('direccion_residencia', 70)->nullable();
            $table->string('telefono_fijo')->nullable();
            $table->string('celular')->nullable();
            $table->string('ciudad_residencia', 60)->nullable();
            $table->string('fuente_recursos', 30)->nullable();
            $table->string('ocupacion_asegurado', 70)->nullable();
            $table->string('eps_asegurado', 70)->nullable();
            $table->string('entidad_pagadora_sucursal', 150)->nullable();

            //BENEFICIARIOS DEL SOLICITANTE STEP 4
            $table->string('nombres_s1', 70)->nullable();
            $table->string('apellidos_s1', 70)->nullable();
            $table->string('genero_s1', 70)->nullable();
            $table->string('parentesco_s1', 70)->nullable();
            $table->unsignedInteger('edad_s1')->nullable();
            $table->integer('porcentaje_s1')->nullable();
            $table->string('tipo_identidad_s1', 70)->nullable();
            $table->string('nombres_s2', 70)->nullable();
            $table->string('apellidos_s2', 70)->nullable();
            $table->string('genero_s2', 70)->nullable();
            $table->string('parentesco_s2', 70)->nullable();
            $table->unsignedInteger('edad_s2')->nullable();
            $table->integer('porcentaje_s2')->nullable();
            $table->string('tipo_identidad_s2', 70)->nullable();
            $table->string('nombres_s3', 70)->nullable();
            $table->string('apellidos_s3', 70)->nullable();
            $table->string('genero_s3', 70)->nullable();
            $table->string('parentesco_s3', 70)->nullable();
            $table->unsignedInteger('edad_s3')->nullable();
            $table->integer('porcentaje_s3')->nullable();
            $table->string('tipo_identidad_s3', 70)->nullable();

            //PRODUCTOS DEL FUNCIONARIO STEP 5
            $table->string('servicios_prevision_exequial', 8)->nullable();
            $table->string('beneficiario_diario_inc_temp', 8)->nullable();
            $table->string('serv_prevision_exequial_mascotas', 8)->nullable();
            $table->string('serv_prevision_salud', 8)->nullable();
            $table->string('otro', 8)->nullable();
            $table->string('cual', 100)->nullable();

            //DECLARACION DE ASEGURABILIDAD STEP 6
            $table->string('cancer', 8)->nullable();
            $table->string('corazon', 8)->nullable();
            $table->string('diabetes', 8)->nullable();
            $table->string('enf_hepaticas', 8)->nullable();
            $table->string('enf_neurologicas', 8)->nullable();
            $table->string('pulmones', 8)->nullable();
            $table->string('presion_arterial', 8)->nullable();
            $table->string('rinones', 8)->nullable();
            $table->string('infeccion_vih', 8)->nullable();
            $table->string('perdida_funcional_anatomica', 8)->nullable();
            $table->string('accidentes_labores_ocupacion', 8)->nullable();
            $table->string('hospitalizacion_intervencion_quirurgica', 8)->nullable();
            $table->string('enfermedad_diferente', 8)->nullable();
            $table->string('enf_cerebrovasculares', 8)->nullable();
            $table->string('cirugias', 8)->nullable();
            $table->string('alcoholismo', 8)->nullable();
            $table->string('tabaquismo', 8)->nullable();
            $table->string('enf_congenitas', 8)->nullable();
            $table->string('enf_colageno', 8)->nullable();
            $table->string('enf_hematologicas', 8)->nullable();
            $table->text('descripcion_de_enfermedades')->nullable();

            // REFERENCIAS LABORALES STEP 7
            $table->string('nombres_apellidos_r1', 70)->nullable();
            $table->string('telefono_r1', 15)->nullable();
            $table->string('entidad_r1', 70)->nullable();
            $table->string('nombres_apellidos_r2', 70)->nullable();
            $table->string('telefono_r2', 15)->nullable();
            $table->string('entidad_r2', 70)->nullable();
            $table->string('nombres_apellidos_r3', 70)->nullable();
            $table->string('telefono_r3', 15)->nullable();
            $table->string('entidad_r3', 70)->nullable();

            // AFILIACION MASCOTAS STEP 8
            $table->string('tienes_mascotas', 3)->nullable();
            $table->string('proteger_mascotas', 3)->nullable();
            $table->string('nombre_m1', 50)->nullable();
            $table->string('tipo_m1', 30)->nullable();
            $table->string('raza_m1', 30)->nullable();
            $table->string('color_m1', 30)->nullable();
            $table->string('genero_m1', 10)->nullable();
            $table->string('edad_m1', 5)->nullable();
            $table->decimal('valor_prima_m1', 10, 2)->nullable();
            $table->string('nombre_m2', 50)->nullable();
            $table->string('tipo_m2', 30)->nullable();
            $table->string('raza_m2', 30)->nullable();
            $table->string('color_m2', 30)->nullable();
            $table->string('genero_m2', 10)->nullable();
            $table->string('edad_m2', 5)->nullable();
            $table->decimal('valor_prima_m2', 10, 2)->nullable();
            $table->string('nombre_m3', 50)->nullable();
            $table->string('tipo_m3', 30)->nullable();
            $table->string('raza_m3', 30)->nullable();
            $table->string('color_m3', 30)->nullable();
            $table->string('genero_m3', 10)->nullable();
            $table->string('edad_m3', 5)->nullable();
            $table->decimal('valor_prima_m3', 10, 2)->nullable();

            $table->string('status', 10)->default('1');
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
        Schema::dropIfExists('inabilities');
    }
}
