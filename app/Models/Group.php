<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'erp_groups';

    use HasFactory;

    protected $fillable = [
        'name',
        'parent_group_id',
        'status',
        'group_id',
        'company_id',
        'organization_id'
    ];

    public function ledgers()
    {
        return $this->hasMany(Ledger::class, 'ledger_group_id', 'id');
    }

    // Relationship to get the parent group
    public function parent()
    {
        return $this->belongsTo(Group::class, 'parent_group_id','id');
    }

    // Relationship to get child groups
    public function children()
    {
        return $this->hasMany(Group::class, 'parent_group_id', 'id');
    }

    // Optionally, if you want to get all item details related to this group
    public function itemDetails()
    {
        return $this->hasManyThrough(ItemDetail::class, Ledger::class, 'ledger_group_id', 'ledger_id', 'id', 'id');
    }

    public function getAllChildIds()
    {
        $ids = [];

        // Fetch all children of the current category
        $childGroups = $this->children;

        foreach ($childGroups as $child) {
            // Add the child's ID to the array
            $ids[] = $child->id;

            // Recursively get IDs of the child's children
            $ids = array_merge($ids, $child->getAllChildIds());
        }

        return $ids;
    }

    public function getGroupLedgerSummary()
    {
        $ledgers = $this->ledgers;

        $totalCredit = $ledgers->sum('credit_amt');
        $totalDebit = $ledgers->sum('debit_amt');

        // Fetch all item details related to the ledgers in this group
        $itemDetails = ItemDetail::whereIn('ledger_id', $ledgers->pluck('id'))->get();

        // Calculate total credits and debits from item details
        $totalItemCredit = $itemDetails->sum('credit_amt');
        $totalItemDebit = $itemDetails->sum('debit_amt');

        // Assuming first closing is the opening balance
        $firstClosing = $ledgers->first()->created_at ?? null;

        // Calculate opening balance (if needed, based on your logic)
        $openingBalance = $this->calculateOpeningBalance($firstClosing);

        // Closing balance calculation
        $closingBalance = $openingBalance + $totalCredit + $totalItemCredit - $totalDebit - $totalItemDebit;

        return [
            'total_credit' => $totalCredit + $totalItemCredit,
            'total_debit' => $totalDebit + $totalItemDebit,
            'first_closing' => $firstClosing,
            'opening_balance' => $openingBalance,
            'closing_balance' => $closingBalance,
            'ledgers' => $ledgers,
            'item_details' => $itemDetails,
        ];
    }
}
