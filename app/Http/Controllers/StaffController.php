<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            // return redirect()->back()->with('error', 'You still have pending transaction with this item!');
        }
        if ($request->quantity == 0 || $request->quantity == '') {
            return response()->json([
                'failed' => 'Please put some value!'
            ]);
        }
        $transaction_item = Product::where('id', $request->item_id)->first();
        if ($transaction_item->quantity < $request->quantity) {
            return response()->json([
                'failed' => "Please put valid quantity based on available stock!"
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
        // return redirect()->back()->with('message', 'Item requested successfully');
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

    public function fetchProduct(Request $request)
    {
        $product_name = $request->product_name;
        $output = '';

        if ($product_name != '') {
            $products = DB::table('products')
                ->where('name', 'like', "%{$product_name}%")
                ->orWhere('category', 'like', "%{$product_name}%")
                ->orderByDesc('quantity')
                ->get();
        } else {
            $products = DB::table('products')->orderByDesc('quantity')->get();
        }

        $total_row = $products->count();

        if ($total_row > 0) {
            foreach ($products as $product) {
                $output .= '<tr class="text-center">
                            <td>' . $product->name . '</td>
                            <td>' . $product->category . '</td>
                            <td>' . $product->quantity . '</td>
                            <td>
                                <form class="d-flex" id="add-request-' . $product->id . '" action="' . route('add-request') . '" method="POST">
                                    ' . csrf_field() . '
                                    <input type="hidden" name="user_id" value="' . auth()->user()->id . '">
                                    <input type="hidden" name="item_id" value="' . $product->id . '">
                                    <input required type="number" name="quantity" class="form-control" min="1" max="' . $product->quantity . '" ' . ($product->quantity <= 0 || $product->status == 'unavailable' ? 'disabled' : '') . '>
                                    ' . ($product->quantity <= 0 || $product->status == 'unavailable' ? '<p class="text-danger">unavailable</p>' : '<button type="submit" class="btn-sm btn btn-success requestbutton">request</button>') . '
                                </form>
                            </td>
                        </tr>';
            }
        } else {
            $output = '<tr>
                        <td colspan="5" class="text-danger">Nothing found.</td>
                    </tr>';
        }

        return response()->json([
            'products' => $output
        ]);
    }

    public function accept(Request $request)
    {
        $id = $request->id;
        $trans = Transaction::find($id);

        $trans->status = 'approved';
        $trans->update();

        return response()->json([
            'success' => 'Transaction Accepted'
        ]);
    }
    public function decline(Request $request)
    {
        $id = $request->id;
        $trans = Transaction::find($id);
        $trans->status = 'declined';
        $trans->update();

        return response()->json([
            'success' => 'Transaction Declined!'
        ]);
    }
    public function release(Request $request)
    {
        $id = $request->id;
        $trans = Transaction::find($id);
        $product = Product::find($trans->item_id);

        // $stock = Stock::find($product->id);
        $stock = new Stock();
        $stock->item_id = $product->id;
        $stock->quantity = 0;
        $stock->issued = $trans->quantity;
        $stock->beginning_balance = $product->quantity;
        $stock->ending_balance = $product->quantity - $trans->quantity;
        $stock->supplier = '';
        $stock->save();


        $ending_balance = $product->quantity - $trans->quantity;
        $product->quantity = $ending_balance;
        $product->update();
        $trans->status = 'released';
        $trans->update();

        return response()->json([
            'success' => 'Transaction Released!'
        ]);
    }

    // POS
    public function pointOfSale()
    {
        $items = Product::all();
        $carts = Cart::where('status', 'ongoing')->get();
        $total_price = $carts->sum(function ($cart) use ($items) {
            $item = $items->where('id', $cart->item_id)->first();
            return $item->price * $cart->quantity;
        });
        return view('staff.pos', compact('items', 'carts', 'total_price'));
    }
    // get barcode
    public function getBarcode(Request $request)
    {
        $barcode = $request->item_barcode;
        $item = Product::where('barcode', $barcode)->first();
        if ($item) {
            $existing = Cart::where('item_id', $item->id)->first();
            if ($existing) {
                // add cart quantity
                $existing->quantity += 1;
                $existing->save();

                // return response()->json([
                //     'added' => "Item Quantity Updated"
                // ]);
            } else {

                $cart = new Cart();
                $cart->item_id = $item->id;
                $cart->save();



                // return response()->json([
                //     'added' => "Item Added"
                // ]);
            }
        } else {
            return response()->json([
                'error' => 'Item not found.'
            ]);
        }
    }
    public function resetCart()
    {
        $carts = Cart::all();

        foreach ($carts as $cart) {
            $cart->delete();
        }

        return;
    }
    public function cartQuantity(Request $request)
    {
        $id = $request->id;
        $cart = Cart::find($id);
        $item = Product::where('id', $cart->item_id)->first();
        return response()->json([
            'quantity' => $cart->quantity,
            'item' => $item
        ]);
    }
    public function updateQuantity(Request $request)
    {
        $id = $request->item_id;
        $cart = Cart::find($id);
        $cart->quantity = $request->quantity;
        $cart->save();
        return response()->json([
            'success' => "Quantity updated!"
        ]);
    }
    public function removeCart(Request $request)
    {
        $id = $request->id;
        $cart = Cart::find($id);
        $cart->delete();
        return response()->json([
            'error' => "Item removed!"
        ]);
    }
    public function proceedPurchase(Request $request)
    {
        // get all the items in the cart
        $carts = Cart::all();

        foreach ($carts as $cart) {
            // subtract from product quantity
            $product = Product::where('id', $cart->item_id)->first();
            $product->quantity -= $cart->quantity;
            $product->save();

            // create new Stock
            $stock = new Stock();
            $stock->item_id = $cart->item_id;
            $stock->quantity = 0;
            $stock->issued = $cart->quantity;
            $stock->beginning_balance = $product->quantity;
            $stock->ending_balance = $product->quantity - $cart->quantity;
            // $stock->supplier = '';
            $stock->save();
        }


        // Initialize arrays to store item_ids and quantities
        $itemIds = [];
        $quantities = [];

        foreach ($carts as $cart) {
            // Assuming $cart->item_id and $cart->quantity are JSON strings, decode them
            $item_id = json_decode($cart->item_id);
            $quantity = json_decode($cart->quantity);

            // Append values to arrays
            $itemIds[] = $item_id;
            $quantities[] = $quantity;
        }

        // Create a purchase
        $purchase = new Purchase();
        $purchase->invoice_no = 'ABC' . str_pad(Purchase::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $purchase->product_id = $itemIds;
        $purchase->product_quantity = $quantities;
        $purchase->payment = $request->product_payment;
        $purchase->save();



        // delete the cart

        foreach ($carts as $cart) {
            $cart->delete();
        }

        return redirect()->route('purchase-items', ['id' => null]);
    }
    public function purchaseItems($id = null)
    {
        if ($id) {
            $data = Purchase::find($id);
        } else {
            $data = Purchase::latest()->first();
        }

        return view('staff.purchase', compact('data'));
    }

    public function addItem($id)
    {
        // // $item = $id;
        // return response()->json([
        //     'id' => $id
        // ]);

        $existing = Cart::where('item_id', $id)->first();
        if ($existing) {
            // add cart quantity
            $existing->quantity += 1;
            $existing->save();

            // return response()->json([
            //     'added' => "Item Quantity Updated"
            // ]);
        } else {

            $cart = new Cart();
            $cart->item_id = $id;
            $cart->save();



            // return response()->json([
            //     'added' => "Item Added"
            // ]);
        }
    }
}
