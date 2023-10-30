<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProdcut;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function categories()
    {
        $data = Category::paginate(7);
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
        $products = DB::table('products');
        $product_name = $request->product_name;
        $product_category = $request->product_category;

        // Search Name
        if ($product_name) {
            $products = $products->where(function ($query) use ($product_name) {
                $query->where('name', 'like', "%{$product_name}%");;
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
        return view('admin.items', compact('data', 'products'));
    }

    public function addProduct(AddProdcut $request)
    {
        // create new Product
        $data = new Product();
        $data->name = $request->name;
        // $data->quantity = $request->quantity;
        $data->price = $request->price;
        $data->category = $request->category;

        $data->save();


        return response()->json([
            'success' => 'Product added successfully!'
        ]);
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
}
