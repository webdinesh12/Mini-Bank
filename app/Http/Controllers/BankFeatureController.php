<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BankFeatureController extends Controller
{
    public function transactions()
    {
        $title = 'Transaction History';
        $user = auth()->user();
        $bankAccount = $user->bank_account;
        $startDate = request('start_date');
        $endDate = request('end_date');
        $type = request('type');
        $search = request('search');
        $users = User::where('id', '!=', auth()->id())->where('type', 'user')->where('status', 'active')->get();
        $transactions = Transaction::where('bank_account_id', $bankAccount->id)
            ->when($startDate, function ($q) use ($startDate) {
                $q->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
            })
            ->when($type, function ($q) use ($type) {
                $q->where('type', $type);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('remarks', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        $currentBalance = $bankAccount->balance;
        return view('user.transactions', compact('title', 'transactions', 'currentBalance', 'users'));
    }

    public function withdrawlAmount(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'withdrawl_amount' => ['bail', 'required', 'numeric', 'gt:0', 'lte:1000000'],
                'remarks' => ['nullable', 'max:250']
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()]);
            }

            $user = Auth::user();
            if ($user->status != 'active') {
                return response()->json(['success' => 0, 'msg' => "Your account is not active so you cannot made any transaction."]);
            }
            $bankAccount = $user->bank_account;
            $amount = number_format($request->withdrawl_amount, 2, '.', '');
            if ($amount > $bankAccount->balance) {
                return response()->json(['success' => 0, 'msg' => "Withdrawl amount should not greater than your account balance."]);
            }
            $openingAmount = $bankAccount->balance;
            $bankAccount->balance = $bankAccount->balance - $amount;
            $bankAccount->save();
            $bankAccount->refresh();
            $closingAmount = $bankAccount->balance;

            $transaction = new Transaction();
            $transaction->bank_account_id = $bankAccount->id;
            $transaction->type = 'withdrawl';
            $transaction->amount = $amount;
            $transaction->opening_balance = $openingAmount;
            $transaction->closing_balance = $closingAmount;
            $transaction->remarks = trim(strip_tags($request->remarks));
            $transaction->save();
            $transaction->trnsaction_id  = str_pad($transaction->id, 6, 0, STR_PAD_LEFT);
            $transaction->save();
            Session::flash('success', 'Amount withdrawl successfully.');
            DB::commit();
            return response()->json(['success' => 1]);
        } catch (Exception $err) {
            DB::rollBack();
            return response()->json(['success' => 0, 'msg' => $err->getMessage()]);
        }
    }
    public function depositAmount(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'deposit_amount' => ['bail', 'required', 'numeric', 'gt:0', 'lte:1000000'],
                'deposit_remarks' => ['nullable', 'max:250']
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()]);
            }

            $user = Auth::user();
            if ($user->status != 'active') {
                return response()->json(['success' => 0, 'msg' => "Your account is not active so you cannot made any transaction."]);
            }
            $bankAccount = $user->bank_account;
            $amount = number_format($request->deposit_amount, 2, '.', '');

            $openingAmount = $bankAccount->balance;
            $bankAccount->balance = $bankAccount->balance + $amount;
            $bankAccount->save();
            $bankAccount->refresh();
            $closingAmount = $bankAccount->balance;

            $transaction = new Transaction();
            $transaction->bank_account_id = $bankAccount->id;
            $transaction->type = 'deposit';
            $transaction->amount = $amount;
            $transaction->opening_balance = $openingAmount;
            $transaction->closing_balance = $closingAmount;
            $transaction->remarks = trim(strip_tags($request->deposit_remarks));
            $transaction->save();
            $transaction->trnsaction_id  = str_pad($transaction->id, 6, 0, STR_PAD_LEFT);
            $transaction->save();
            Session::flash('success', 'Amount deposit successfully.');
            DB::commit();
            return response()->json(['success' => 1]);
        } catch (Exception $err) {
            DB::rollBack();
            return response()->json(['success' => 0, 'msg' => $err->getMessage()]);
        }
    }
    public function transferAmount(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'transfer_amount' => ['bail', 'required', 'numeric', 'gt:0', 'lte:1000000'],
                'transfer_remarks' => ['nullable', 'max:250'],
                'to_user_id' => ['required', 'exists:users,id']
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()]);
            }

            $user = Auth::user();
            if ($user->status != 'active') {
                return response()->json(['success' => 0, 'msg' => "Your account is not active so you cannot made any transaction."]);
            }
            $reciever = User::find($request->to_user_id);
            if ($reciever->status != 'active') {
                return response()->json(['success' => 0, 'msg' => "Reciever account is not active so you cannot send any amount."]);
            }

            $bankAccount = $user->bank_account;
            $amount = number_format($request->transfer_amount, 2, '.', '');

            if ($amount > $bankAccount->balance) {
                return response()->json(['success' => 0, 'msg' => "Transfer amount should not greater than your account balance."]);
            }

            $openingAmount = $bankAccount->balance;
            $bankAccount->balance = $bankAccount->balance - $amount;
            $bankAccount->save();
            $bankAccount->refresh();
            $closingAmount = $bankAccount->balance;

            $transaction = new Transaction();
            $transaction->bank_account_id = $bankAccount->id;
            $transaction->type = 'transfer';
            $transaction->transfer_type = 'send';
            $transaction->amount = $amount;
            $transaction->opening_balance = $openingAmount;
            $transaction->closing_balance = $closingAmount;
            $transaction->remarks = trim(strip_tags($request->transfer_remarks));
            $transaction->save();
            $transaction->trnsaction_id  = str_pad($transaction->id, 6, 0, STR_PAD_LEFT);
            $transaction->save();

            $recieverBankAcount = BankAccount::where('user_id', $reciever->id)->firstOrFail();
            $recieverOpeningAmount = $recieverBankAcount->balance;
            $recieverBankAcount->balance = $recieverBankAcount->balance + $amount;
            $recieverBankAcount->save();
            $recieverBankAcount->refresh();
            $recieverClosingAmount = $recieverBankAcount->balance;

            $transaction = new Transaction();
            $transaction->bank_account_id = $recieverBankAcount->id;
            $transaction->type = 'transfer';
            $transaction->transfer_type = 'recieved';
            $transaction->amount = $amount;
            $transaction->opening_balance = $recieverOpeningAmount;
            $transaction->closing_balance = $recieverClosingAmount;
            $transaction->remarks = trim(strip_tags($request->transfer_remarks));
            $transaction->save();
            $transaction->trnsaction_id  = str_pad($transaction->id, 6, 0, STR_PAD_LEFT);
            $transaction->save();

            Session::flash('success', 'Amount Transfer successfully.');
            DB::commit();
            return response()->json(['success' => 1]);
        } catch (Exception $err) {
            DB::rollBack();
            return response()->json(['success' => 0, 'msg' => $err->getMessage()]);
        }
    }
}
