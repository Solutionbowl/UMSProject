<?php
namespace App\Models;

use App\Models\User;
use App\Models\Address;
use App\Models\Customer;
use App\Models\InvoiceBook;
use App\Models\Organization;
use App\Traits\DateFormatTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MrnHeaderHistory extends Model
{
    use HasFactory, SoftDeletes, DateFormatTrait;
    protected $table = 'erp_mrn_header_histories';
    protected $fillable = [
        'mrn_header_id', 
        'series_id', 
        'organization_id', 
        'group_id', 
        'company_id', 
        'vendor_id', 
        'purchase_order_id', 
        'mrn_code', 
        'mrn_no', 
        'mrn_date', 
        'document_number', 
        'document_date', 
        'document_status', 
        'revision_number', 
        'revision_date', 
        'approval_level', 
        'reference_number', 
        'mrn_type', 
        'gate_entry_no', 
        'gate_entry_date', 
        'supplier_invoice_no', 
        'supplier_invoice_date', 
        'eway_bill_no', 
        'consignment_no', 
        'transporter_name', 
        'vehicle_no', 
        'billing_to', 
        'ship_to', 
        'billing_address', 
        'shipping_address', 
        'currency_id', 
        'transaction_currency', 
        'org_currency_id', 
        'org_currency_code', 
        'org_currency_exg_rate', 
        'comp_currency_id', 
        'comp_currency_code', 
        'comp_currency_exg_rate', 
        'group_currency_id', 
        'group_currency_code', 
        'group_currency_exg_rate', 
        'sub_total', 
        'total_item_amount', 
        'item_discount', 
        'header_discount', 
        'total_discount', 
        'gst', 
        'gst_details', 
        'taxable_amount', 
        'total_taxes', 
        'total_after_tax_amount', 
        'expense_amount', 
        'total_amount', 
        'item_remark', 
        'final_remarks', 
        'attachment', 
        'status', 
        'created_by', 
        'updated_by', 
        'deleted_by'
    ];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'gst_details' => 'array',
    ];


    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function mrn()
    {
        return $this->belongsTo(MrnHeader::class, 'mrn_header_id');
    }

    public function mrnHeader()
    {
        return $this->belongsTo(MrnHeader::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'series_id');
    }

    public function paymentTerms()
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function items()
    {
        return $this->hasMany(MrnDetailHistory::class, 'mrn_header_history_id');
    }

    public function attributes()
    {
        return $this->hasMany(MrnAttributeHistory::class, 'mrn_header_history_id');
    }

    public function mrn_ted()
    {
        return $this->hasMany(MrnExtraAmountHistory::class,'mrn_header_history_id');
    }

    public function mrn_ted_tax()
    {
        return $this->hasMany(MrnExtraAmountHistory::class,'mrn_header_history_id')->where('ted_type','Tax');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_to');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'ship_to');
    }

    public function attachment(): void
    {
        $this->addMediaCollection('attachment');
    }

    public function organizationAddress()
    {
        return $this->morphOne(Address::class, 'addressable')->where('type', 'default');
    }

    public function billingPartyAddress()
    {
        return $this->morphOne(Address::class, 'addressable')->where('type', 'billing');
    }

    /*Header Level Discount*/
    public function headerDiscount()
    {
        return $this->hasMany(MrnExtraAmountHistory::class, 'mrn_header_history_id')->where('ted_level', 'H')->where('ted_type','Discount');
    }

    /*Total discount header level total_header_disc_amount*/
    public function getTotalHeaderDiscAmountAttribute()
    {
        return $this->headerDiscount()->sum('ted_amount');
    }

    /*Header Level Expense*/
    public function expenses()
    {
        return $this->hasMany(MrnExtraAmountHistory::class,'mrn_header_history_id')->where('ted_type', '=', 'Expense')
            ->where('ted_level', '=', 'H');
    }

    public function getTotalExpAssessmentAmountAttribute()
    {
        return ($this->total_item_amount + $this->total_taxes - $this->total_discount);
    }
}
