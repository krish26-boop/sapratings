<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

use Illuminate\Support\Str;


class ProductManageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (request()->ajax()) {
            return datatables()->of(Product::select('*'))
                ->addColumn('action', 'book-action')
                ->addColumn('image', 'image')
                ->rawColumns(['action', 'image'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('products_list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $productId = $request->id;
        if ($productId) {

            $product = Product::find($productId);
            if ($request->hasFile('image')) {
                $img =  $request->file('image');
                $extension = $img->getClientOriginalExtension();
                // create a new file name
                $new_name = date('Y-m-d') . '-' . Str::random(10) . '.' . $extension;
                // move file to public/images/new and use $new_name
                $img->move(public_path('images'), $new_name);

                $product->image = $new_name;
            }
        } else {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           ]);

            $img =  $request->file('image');
            $extension = $img->getClientOriginalExtension();
            // create a new file name
            $new_name = date('Y-m-d') . '-' . Str::random(10) . '.' . $extension;
            // move file to public/images/new and use $new_name
            $img->move(public_path('images'), $new_name);
           
            $product = new Product;
            $product->image = $new_name;
        }

        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->min_qty = $request->min_qty;
        $product->max_qty = $request->max_qty;
        $product->status = $request->status;
        $product->save();

        return Response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
        $where = array('id' => $request->id);
        $product  = Product::where($where)->first();

        return Response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $book = Product::where('id', $request->id)->delete();

        return Response()->json($book);
    }
}
