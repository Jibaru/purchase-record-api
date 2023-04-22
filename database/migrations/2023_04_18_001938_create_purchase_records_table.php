<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('period', 8); // pk 1
            $table->string('unique_operation_code', 40); // pk 2
            $table->string('correlative_accounting_entry_number', 10); // pk 3
            $table->string('issue_date', 10); // 4
            $table->string('due_date', 10)->nullable(); // 5
            $table->string('voucher_type', 2); // 6 tipo comprobante
            $table->string('voucher_series', 20)->nullable(); // 7 serie comprobante
            $table->string('dua_or_dsi_issue_year', 4)->nullable(); // 8 anio emision
            $table->string('voucher_number', 20); // 9 numero_comprobante
            $table->string('daily_operations_total_amount', 20)->nullable(); // 10 importe_total_operaciones_diarias
            $table->string('supplier_document_type', 1)->nullable(); // 11 tipo de documento
            $table->string('supplier_document_number', 15)->nullable(); // 12 numero de documento
            $table->string('supplier_document_denomination', 100)->nullable(); // 13 denominacion_proveedor
            $table->decimal('first_tax_base', 14, 2)->nullable(); // 14 base_imponible_1
            $table->decimal('first_igv_amount', 14, 2)->nullable(); // 15
            $table->decimal('second_tax_base', 14, 2)->nullable(); // 16
            $table->decimal('second_igv_amount', 14, 2)->nullable(); // 17
            $table->decimal('third_tax_base', 14, 2)->nullable(); // 18
            $table->decimal('third_igv_amount', 14, 2)->nullable(); // 19
            $table->decimal('acquisitions_untaxed_value', 14, 2)->nullable(); // 20 valor adquisiciones no gravadas
            $table->decimal('isc_amount', 14, 2)->nullable(); // 21 monto_del_impuesto_selectivo_al_consumo
            $table->decimal('icbper_amount', 14, 2)->nullable(); // 22
            $table->decimal('other_concepts', 14, 2)->nullable(); // 23 otros_conceptos
            $table->decimal('acquisitions_total_amount', 14, 2)->nullable(); // 24 importe total adquisiciones
            $table->string('currency_code', 3)->nullable(); // 25 codigo_de_la_moneda
            $table->decimal('exchange_rate', 4, 3)->nullable(); // 26 tipo_de_cambio
            $table->string('modify_issue_date', 10)->nullable(); // 27 fecha emisión modifica
            $table->string('modify_voucher_type', 2)->nullable(); // 28 tipo comprobante pago modifica
            $table->string('modify_voucher_series', 3)->nullable(); // 29 numero_serie_comprobante_pago_modifica
            $table->string('customs_dependency_code', 3)->nullable(); // 30 codigo_dependencia_aduanera
            $table->string('modify_voucher_number', 20)->nullable(); // 31 numero_comprobante_modifica
            $table->string('issue_date_certificate_deposit_detraction', 10)->nullable(); // 32 fecha_emision_constancia_deposito_detraccion
            $table->string('number_certificate_deposit_detraction', 24)->nullable(); // 33 numero_constancia_deposito_detraccion
            $table->string('voucher_mark_subject_to_retention', 1)->nullable(); // 34 marca_comprobante_pago_sujeto_retencion
            $table->string('classification_goods_services_acquired', 1)->nullable(); // 35 clasificacion_bienes_servicios_adquiridos
            $table->string('contract_identification', 12)->nullable(); // 36 identificacion_contrato
            $table->string('error_type_one', 1)->nullable(); // 37
            $table->string('error_type_two', 2)->nullable(); // 38
            $table->string('error_type_three', 3)->nullable(); // 39
            $table->string('error_type_four', 4)->nullable(); // 40
            $table->string('vouchers_canceled_with_payment_methods', 1)->nullable(); // 41 comprobantes_de_pago_cancelados_con_medios_de_pago
            $table->string('state', 1)->nullable(); // 42
            $table->uuid('voucher_id')->unique();
            $table->foreign('voucher_id')
                ->on('vouchers')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_records');
    }
};
