<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Cloudinary;
use App\Category;
use App\MultiOptionsCategory;

class CategoryController extends AdminController{

    // type : get -> to add new
    public function AddGet(){
        return view('admin.category_form');
    }

    // type : post -> add new category
    public function AddPost(Request $request){
        $image_name = $request->file('image')->getRealPath();
        $imagereturned = Cloudinary::upload($image_name);
        $image_id = $imagereturned->getPublicId();
        $image_format = $imagereturned->getExtension();      
        $image_new_name = $image_id.'.'.$image_format;
        $category = new Category();
        $category->image = $image_new_name;
        $category->title_en = $request->title_en;
        $category->title_ar = $request->title_ar;
        $cat = Category::create(['image' => $image_new_name, 'title_en' => $request->title_en, 'title_ar' => $request->title_ar]);
        MultiOptionsCategory::create(['multi_option_id' => 8, 'category_id' => $cat['id']]);
        return redirect('admin-panel/categories/show'); 
    }

    // get all categories
    public function show(){
        $data['categories'] = Category::where('deleted' , 0)->orderBy('id' , 'desc')->get();
        return view('admin.categories' , ['data' => $data]);
    }

    // get edit page
    public function EditGet(Request $request){
        $data['category'] = Category::find($request->id);
        return view('admin.category_edit' , ['data' => $data ]);
    }

    // edit category
    public function EditPost(Request $request){
        $category = Category::find($request->id);
        if($request->file('image')){
            $image_name = $request->file('image')->getRealPath();
            $imagereturned = Cloudinary::upload($image_name);
            $image_id = $imagereturned->getPublicId();
            $image_format = $imagereturned->getExtension();      
            $image_new_name = $image_id.'.'.$image_format;
            $category->image = $image_new_name;
        }

        $category->title_en = $request->title_en;
        $category->title_ar = $request->title_ar;
        $category->save();
        return redirect('admin-panel/categories/show');
    }

    // delete category
    public function delete(Request $request){
        $category = Category::find($request->id);
        $category->deleted = 1;
        $category->save();
        return redirect()->back();
    }

    // details
    public function details(Category $category) {
        $data['category'] = $category;

        return view('admin.category_details', ['data' => $data]);
    }

    

}