<?php

namespace Core\PurchaseRecords\Domain\Entities\Builders;

use Core\PurchaseRecords\Domain\Entities\PurchaseRecord;
use Core\Vouchers\Application\Parser\Values\Invoice;
use Exception;

interface PurchaseRecordBuilder
{
    /**
     * Field 01
     *
     * @return void
     */
    public function setPeriodToNow(): void;

    /**
     * Field 04
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setIssueDateFromInvoice(Invoice $invoice): void;

    /**
     * Field 06
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setVoucherTypeFromInvoice(Invoice $invoice): void;

    /**
     * Field 05
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setDueDateFromInvoice(Invoice $invoice): void;

    /**
     * Field 07
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setVoucherSeriesFromInvoice(Invoice $invoice): void;

    /**
     * Field 08
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setDuaOrDsiIssueYearFromInvoice(Invoice $invoice): void;

    /**
     * Field 09
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setVoucherNumberFromInvoice(Invoice $invoice): void;

    /**
     * Field 10
     *
     * @param Invoice $invoice
     * @return void
     */
    public function setDailyOperationsTotalAmountFromInvoice(Invoice $invoice): void;

    /**
     * Fields 11, 12, 13
     *
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function setSupplierInformationFromInvoice(Invoice $invoice): void;

    /**
     * Fields 14, 15, 16, 17, 18, 19
     *
     * @param Invoice $invoice
     * @return void
     */
    public function setTaxBasesAndIgvAmountsFromInvoice(Invoice $invoice): void;

    /**
     * Field 43
     *
     * @param Invoice $invoice
     * @return void
     */
    public function setPayableAmountFromInvoice(Invoice $invoice): void;

    /**
     * Fields 44, 45
     *
     * @param Invoice $invoice
     * @return void
     */
    public function setDetractionInformationFromInvoice(Invoice $invoice): void;

    public function build(): PurchaseRecord;
}
