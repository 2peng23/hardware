<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProdcut;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function categories()
    {
        $data = Category::paginate(5);
        return view('admin.categories', compact('data'));
    }
    public function addCategory(Request $request)
    {
        // Check if a category with the same name already exists
        $existingCategory = Category::where('name', $request->name)->first();

        if ($existingCategory) {
            return redirect()->back()->with('error', 'Category with the same name already exists!');
        } else {
            $data = new Category();
            $data->name = $request->name;
            // image
            $photo = $request->image;
            $photoname = time() . '.' . $photo->getClientOriginalExtension();
            $request->image->move('images', $photoname);
            $data->image = $photoname;
            $data->save();
            return redirect()->back()->with('message', 'Category Added!');
        }
    }


    public function items(Request $request)
    {
        $products = DB::table('products')->where('status', 'available');
        $product_name = $request->product_name;
        $product_category = $request->product_category;
        $date_range = $request->date_range;

        // Search Name
        if ($product_name) {
            $products = $products->where(function ($query) use ($product_name) {
                $query->where('name', 'like', "%{$product_name}%")
                    ->orwhere('item_id', 'like', "%{$product_name}%");;
            });
        }

        // Search Category
        if ($product_category) {
            $products = $products->where(function ($query) use ($product_category) {
                $query->where('category', 'like', "%{$product_category}%");;
            });
        }

        // Search by Date Range
        // if ($date_range) {
        //     $dateArray = explode(' - ', $date_range);
        //     $start_date = \Carbon\Carbon::createFromFormat('m/d/Y', $dateArray[0])->startOfDay();
        //     $end_date = \Carbon\Carbon::createFromFormat('m/d/Y', $dateArray[1])->endOfDay();
        //     $products = $products->whereBetween('created_at', [$start_date, $end_date]);
        // }
        $products = $products->paginate(5);
        $products->appends(['product_name' => $product_name]); // Add search query to pagination links
        $products->appends(['product_category' => $product_category]); // Add search query to pagination links
        // $products->appends(['date_range' => $date_range]); // Add search query to pagination links


        $data = Category::all();
        $status = 'Nothing found.';
        if ($products->count() > 0) {
            return view('admin.items', compact('products', 'data', 'product_name', 'product_category'));
        } else {
            return response()->json([
                'status' => $status,
            ]);
        }
    }

    public function search(Request $request)
    {
        $products = DB::table('products');
        $product_name = $request->product_name;
        $product_category = $request->product_category;

        // Search Name
        if ($product_name) {
            $products = $products->where(function ($query) use ($product_name) {
                $query->where('name', 'like', "%{$product_name}%")
                    ->orwhere('item_id', 'like', "%{$product_name}%");;
            });
        }

        // Search Category
        if ($product_category) {
            $products = $products->where(function ($query) use ($product_category) {
                $query->where('category', 'like', "%{$product_category}%");;
            });
        }
        $products = $products->paginate(10);
        $products->appends(['product_name' => $product_name]); // Add search query to pagination links
        $products->appends(['product_category' => $product_category]); // Add search query to pagination links


        $data = Category::all();
        if ($products->count() >= 1) {
            return view('admin.pagination', compact('data', 'products'))->render();
        } else {
            return response()->json([
                'status' => 'Nothing found.',
            ]);
        }
    }

    public function addProduct(AddProdcut $request)
    {
        // Get the latest product
        $latestProduct = Product::orderBy('id', 'desc')->first();
        $latestItemId = $latestProduct ? (int) $latestProduct->item_id : 0;
        $itemId = str_pad($latestItemId + 1, 4, '0', STR_PAD_LEFT);

        // Check if the product already exists
        $existingProduct = Product::where('name', $request->name)->first();
        if ($existingProduct) {
            // return response()->json([
            //     'error' => 'Product name already exists!'
            // ]);
            // return redirect()->back()->with('error', 'Product name already exist!');
            return response()->json([
                'failed' => 'Product already exist!'
            ]);
        }

        // Create a new product
        $product = new Product();
        $product->item_id = $itemId;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category = $request->category;

        $product->save();

        // return response()->json([
        //     'success' => 'Product added successfully!'
        // ]);
        // return redirect()->back()->with('message', 'Product successfully added!');
        return response()->json([
            'success' => 'Product added successfully!'
        ]);
    }

    public function editProduct($id)
    {
        $product = Product::find($id);
        return response()->json([
            'product' => $product,
        ]);
    }

    public function updateProduct(Request $request)
    {
        $item_id = $request->item_id;
        $item = Product::find($item_id);
        $item->name = $request->name;
        $item->price = $request->price;
        $item->category = $request->category;
        $item->update();
        return response()->json([
            'success' => 'Item updated successfully!'
        ]);
    }

    // public function deleteProduct($id)
    // {
    //     $product = Product::find($id);
    //     $product->delete();
    //     return redirect()->back()->with('error', 'Product Deleted!');
    // }
    public function archiveProduct(Request $request)
    {
        $id = $request->item_id;
        $product = Product::find($id);
        $product->status = 'unavailable';
        $product->update();

        return response()->json([
            'success' => 'Product moved to unavailable items!'
        ]);
    }
    public function available(Request $request)
    {
        $id = $request->item_id;
        $product = Product::find($id);
        $product->status = 'available';
        $product->update();

        return response()->json([
            'success' => 'Product moved to available items!'
        ]);
    }
    public function unavailable()
    {
        $data = Category::all();
        $products = Product::where('status', 'unavailable')->paginate(2);
        return view('admin.unavailable', compact('products', 'data'));
    }



    public function editStock($id)
    {
        $product = Product::find($id); // Use find instead of where to get the user by its ID
        return response()->json([
            'product' => $product,
        ]);
    }

    public function addStock(Request $request)
    {
        $item_id = $request->item_id;
        $item = Product::find($item_id);

        if (!$item) {
            return response()->json([
                'error' => 'Product not found!'
            ], 404); // Return an error response with status code 404
        }

        $item->quantity += $request->quantity; // Shorter way to add to quantity
        $item->save(); // Use save instead of update

        $stock = new Stock();
        $stock->item_id = $request->item_id; //generate random 8 numbers
        $stock->beginning_balance =  $request->beginning_balance;
        $stock->ending_balance =  $request->ending_balance;
        $stock->quantity = $request->quantity;
        $stock->supplier = $request->supplier;
        $stock->save();

        return response()->json([
            'success' => 'Stock added successfully!'
        ], 200); // Return a success response with status code 200
    }
    // edit critical stock
    public function editCritical($id)
    {
        $product = Product::find($id); // Use find instead of where to get the user by its ID
        return response()->json([
            'product' => $product,
        ]);
    }

    public function updateCritical(Request $request)
    {
        $product_id = $request->item_id;
        $product = Product::find($product_id);
        $product->critical_stock = $request->critical_stock;
        $product->update();
        return response()->json([
            'success' => 'Critical stock updated successfully!'
        ], 200); // Return a success response with status code 200
    }


    public function inventory(Request $request)
    {
        $products = DB::table('products');
        $product_name = $request->product_name;
        $product_category = $request->product_category;
        $date_range = $request->date_range;

        // Search Name
        if ($product_name) {
            $products = $products->where(function ($query) use ($product_name) {
                $query->where('name', 'like', "%{$product_name}%")
                    ->orwhere('item_id', 'like', "%{$product_name}%");;
            });
        }

        // Search Category
        if ($product_category) {
            $products = $products->where(function ($query) use ($product_category) {
                $query->where('category', 'like', "%{$product_category}%");;
            });
        }

        // Search by Date Range
        if ($date_range) {
            $dateArray = explode(' - ', $date_range);
            $start_date = \Carbon\Carbon::createFromFormat('m/d/Y', $dateArray[0])->startOfDay();
            $end_date = \Carbon\Carbon::createFromFormat('m/d/Y', $dateArray[1])->endOfDay();
            $products = $products->whereBetween('created_at', [$start_date, $end_date]);
        }
        $products = $products->paginate(5);
        $products->appends(['product_name' => $product_name]); // Add search query to pagination links
        $products->appends(['product_category' => $product_category]); // Add search query to pagination links
        $products->appends(['date_range' => $date_range]); // Add search query to pagination links


        $category = Category::all();
        $status = 'Nothing found.';
        if ($products->count() > 0) {
            return view('admin.inventory', compact('products', 'category', 'product_name', 'product_category', 'date_range'));
        } else {
            return response()->json([
                'status' => $status,
            ]);
        }
    }

    public function request()
    {
        $transaction = Transaction::orderBy('updated_at', 'desc')->paginate(5);
        return view('admin.request', compact('transaction'));
    }
}
