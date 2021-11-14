<?php
namespace App\Http\Controllers\Admin\categories;
use App\Category_option;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Cloudinary;
use App\Category;
use Illuminate\Support\Facades\Lang;

class CategoryController extends AdminController{
    // type : get -> to add new
    public function AddGet(){
        return view('admin.categories.create');
    }
    // type : post -> add new category
    public function AddPost(Request $request){
        $image_name = $request->file('image')->getRealPath();
        $imagereturned = Cloudinary::upload($image_name);
        $image_id = $imagereturned->getPublicId();
        $image_format = $imagereturned->getExtension(); 
        $image_new_name = $image_id.'.'.$image_format;

        $category = new Category();
        $category->title_en = $request->title_en;
        $category->title_ar = $request->title_ar;
        $category->image = $image_new_name;
        $category->save();

        session()->flash('success', trans('messages.added_s'));
        return redirect('admin-panel/categories/show');
    }
    // get all categories
    public function show(Request $request){
        $lang = Lang::getLocale();
        $data['categories'] = Category::where('deleted', 0)->select('id', 'title_' . $lang . ' as title', 'image', 'show_home')->get()->makeHidden('subCategories')
        ->map(function ($cat) {
            
            if ($cat->subCategories && count($cat->subCategories) > 0) {
                $hasProducts = false;
                for ($i = 0; $i < count($cat->subCategories); $i++) {
                    if (count($cat->subCategories[$i]->products) > 0) {
                        $hasProducts = true;
                    }
                }

                if ($hasProducts) {
                    $cat->next_level = true;
                }

            }

            return $cat;
        });
        return view('admin.categories.index' , ['data' => $data]);
    }
    // get edit page
    public function EditGet(Request $request){
        $data['category'] = Category::find($request->id);
        return view('admin.categories.edit' , ['data' => $data ]);
    }
    // edit category
    public function EditPost(Request $request){
        $category = Category::find($request->id);
        if($request->file('image')){
            // $image = $category->image;
            // $publicId = substr($image, 0 ,strrpos($image, "."));
            // if($publicId != null ){
            //     Cloudder::delete($publicId);
            // }
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
    // get category products
    public function category_products(Category $category) {
        $data['products'] = $category->products;
        if (app()->getLocale() == 'en') {
            $data['category'] = $category->title_en;
        }else {
            $data['category'] = $category->title_ar;
        }
        return view('admin.products.products', ['data' => $data]);
    }

    // show in home
    public function showInHome(Category $category, Request $request) {
        $category->update(['show_home' => $request->status]);

        return redirect()->back()->with('success', __('messages.updated_successfully'));
    }
}
