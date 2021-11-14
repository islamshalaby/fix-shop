<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\Option;
use App\Category;
use App\OptionValue;
use App\PropertiesCategory;

class OptionsController extends AdminController{
    // get all options
    public function show(){
        $data['options'] = Option::orderBy('id' , 'desc')->get();
        return view('admin.options' , ['data' => $data]);
    }

    // get all properties Categories
    public function show_properties_categories(){
        $data['categories'] = PropertiesCategory::where('deleted', 0)->orderBy('id' , 'desc')->get();
        return view('admin.options_categories' , ['data' => $data]);
    }

    // add get
    public function addGet() {
        $data['categories'] = Category::where('deleted', 0)->orderBy('id' , 'desc')->get();

        return view('admin.option_form', ['data' => $data]);
    }

    // add get properties category
    public function addGetPropertyCategory() {
        return view('admin.options_category_form');
    }

    // add post
    public function addPost(Request $request) {
        $request->validate([
            'title_en' => 'required',
            'title_ar' => 'required',
            'category_ids' => 'required',
            'property_values_en' => 'required',
            'property_values_ar' => 'required'
        ]);
        $values_en = explode(',', $request->property_values_en);
        $values_ar = explode(',', $request->property_values_ar);
        if (count($values_en) != count($values_ar)) {
            return redirect()->back()->with('fail', __('messages.values_number_should'));
        }
        $post = $request->except(['category_ids', 'property_values_en', 'property_values_ar']);
        $option = Option::create($post);

        $option->categories()->sync($request->category_ids);
        
        if (isset($request->property_values_en) && isset($request->property_values_ar)) {
        
            if (count($values_en) == count($values_ar)) {
                for ($i = 0; $i < count($values_en); $i ++) {
                    OptionValue::create([
                    'option_id' => $option->id,
                    'value_en' => trim($values_en[$i], ' '),
                    'value_ar' => trim($values_ar[$i], ' ')
                    ]);
                }
            }
        }


        return redirect()->route('options.index');
    }

    // add post properties category
    public function addPostPropertiesCategory(Request $request) {
        $post = $request->all();

        PropertiesCategory::create($post);

        return redirect()->route('options.categories.index');
    }

    // edit get
    public function editGet(Option $option) {
        $data['option'] = $option;
        $data['categories'] = Category::where('deleted', 0)->orderBy('id' , 'desc')->get();
        $data['categories_array'] = $data['option']->categories()->pluck('categories.id')->toArray();

        return view('admin.option_edit', ['data' => $data]);
    }

    // edit get properties category
    public function editGetPropertiesCategory(PropertiesCategory $category) {
        $data['category'] = $category;

        return view('admin.options_category_edit', ['data' => $data]);
    }

    // edit post
    public function editPost(Request $request, Option $option) {
        $post = $request->except(['category_ids', 'property_values_en', 'property_values_ar']);
        $option->update($post);
        
        if (isset($request->property_values_en) && isset($request->property_values_ar)) {
                $values_en = explode(',', $request->property_values_en);
                
                $values_ar = explode(',', $request->property_values_ar);
            
            if (count($values_en) == count($values_ar)) {
                $option->values()->delete();
                for ($i = 0; $i < count($values_en); $i ++) {
                    OptionValue::create([
                    'option_id' => $option->id,
                    'value_en' => trim($values_en[$i], ' '),
                    'value_ar' => trim($values_ar[$i], ' ')
                    ]);
                }
            }
        }
        

        return redirect()->route('options.index');
    }

    // edit post properties category
    public function editPostPropertiesCategory(Request $request, PropertiesCategory $category) {
        $post = $request->all();

        $category->update($post);

        return redirect()->route('options.categories.index');
    }

    // delete
    public function delete(Option $option) {
        $option->categories()->sync([]);
        $option->values()->delete();
        $option->delete();

        return redirect()->back();
    }

    // delete properties category
    public function deletePropertiesCategory(PropertiesCategory $category) {
        $category->deleted = 1;
        $category->save();

        return redirect()->back();
    }
}