<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            if ($user->usertype == 0) {
                $category_name = $request->category_name;
                $transaction = Transaction::where('user_id', Auth::user()->id)->paginate(2);
                $products = Product::where('category', 'like', "%{$category_name}%")->get();

                $category = Category::all();
                if ($products->count() > 0) {
                    return view('staff.home', compact('category', 'products', 'transaction'));
                } else {
                    return response()->json([
                        'status' => 'Nothing found.'
                    ]);
                }
            } else {

                $pending = Transaction::where('status', 'pending')->count(); //count of pending
                $transactions = Transaction::all(); //all transactions
                $userIds = $transactions->pluck('user_id')->toArray(); //user_id of every transaction
                $itemIds = $transactions->pluck('item_id')->toArray(); //item id of every tranasction
                $transaction_items = Product::whereIn('id', $itemIds)->get();
                $transaction_users = User::whereIn('id', $userIds)->get();

                return view('admin.Home', compact('pending', 'transactions', 'transaction_users', 'transaction_items'));
            }
        }
        return redirect('/login');
    }
}
