<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ProductType;

class ProductTypeController extends AdminController{
    // index
    public function show() {
        $data['types'] = ProductType::orderBy('id', 'desc')->get();

        return view('admin.product_types', ['data' => $data]);
    }

    // add get
    public function AddGet() {
        return view('admin.product_type_form');
    }

    // add post
    public function AddPost(Request $request) {
        $post = $request->all();

        ProductType::create($post);

        return redirect()->route('product_type.index');
    }

    // edit get
    public function EditGet(ProductType $type) {
        $data['type'] = $type;

        return view('admin.product_type_edit', ['data' => $data]);
    }

    // edit post
    public function EditPost(Request $request, ProductType $type) {
        $post = $request->all();

        $type->update($post);

        return redirect()->route('product_type.index');
    }

    // delete
    public function delete(ProductType $type) {
        $type->delete();

        return redirect()->back();
    }

    
}