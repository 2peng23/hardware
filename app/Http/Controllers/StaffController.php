<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function addRequest(Request $request)
    {
        $user_transaction = Transaction::where('user_id', Auth::user()->id)
            ->where('item_id', $request->item_id)
            ->first();

        if ($user_transaction && $user_transaction->status === 'pending') {
            return response()->json([
                'failed' => 'You still have pending transaction with this item!'
            ]);
        }

        $transaction = new Transaction();
        $transaction->transaction_id = $this->generateTransactionId();
        $transaction->user_id = Auth::user()->id; // Use Auth::user()->id
        $transaction->item_id = $request->item_id;
        $transaction->quantity = $request->quantity;
        $transaction->status = 'pending'; // Set the status as pending for the new transaction
        $transaction->save();

        return response()->json([
            'success' => 'Item requested successfully!'
        ]);
    }




    public function generateTransactionId()
    {
        $random_numbers = '';
        for ($i = 0; $i < 8; $i++) {
            $random_numbers .= mt_rand(0, 9);
        }
        return 'TRNSCTN' . $random_numbers;
    }


    public function viewTransaction($id)
    {

        $user = Auth::user();
        $transaction = Transaction::find($id);
        $item = Product::where('id', $transaction->item_id)->first();

        return response()->json([
            'data' => $transaction,
            'requestor' => $user->name,
            'designation' => $user->designation,
            'item' => $item,
        ]);
    }
}
