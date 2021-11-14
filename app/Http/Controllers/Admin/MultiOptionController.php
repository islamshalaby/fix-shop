<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\MultiOption;
use App\Category;
use App\MultiOptionValue;

class MultiOptionController extends AdminController{
    // get all options
    public function show(){
        $data['options'] = MultiOptionValue::where('multi_option_id', 8)->orderBy('id' , 'desc')->get();
        return view('admin.multi_options' , ['data' => $data]);
    }

    // add get
    public function addGet() {
        $data['categories'] = Category::where('deleted', 0)->orderBy('id' , 'desc')->get();

        return view('admin.multi_option_form', ['data' => $data]);
    }

    // add post
    public function addPost(Request $request) {
        $post = $request->except(['category_ids', 'property_values_en', 'property_values_ar']);
        $option = MultiOption::create($post);
        $option->categories()->sync($request->category_ids);
        if (isset($request->property_values_en) && isset($request->property_values_ar)) {
            $values_en = explode(',', $request->property_values_en);
            $values_ar = explode(',', $request->property_values_ar);
        
            if (count($values_en) == count($values_ar)) {
                for ($i = 0; $i < count($values_en); $i ++) {
                    MultiOptionValue::create([
                    'multi_option_id' => $option->id,
                    'value_en' => trim($values_en[$i], ' '),
                    'value_ar' => trim($values_ar[$i], ' ')
                    ]);
                }
            }
        }


        return redirect()->route('multi_options.index');
    }

    // edit get
    public function editGet(MultiOption $option) {
        $data['option'] = $option;
        $data['categories'] = Category::where('deleted', 0)->orderBy('id' , 'desc')->get();
        $data['categories_array'] = $data['option']->categories()->pluck('categories.id')->toArray();

        return view('admin.multi_option_edit', ['data' => $data]);
    }

    // edit post
    public function editPost(Request $request, MultiOption $option) {
        $post = $request->except(['category_ids', 'property_values_en', 'property_values_ar']);
        $option->update($post);
        
        if (isset($request->property_values_en) && isset($request->property_values_ar)) {
                $values_en = explode(',', $request->property_values_en);
                $values_ar = explode(',', $request->property_values_ar);
            if (count($values_en) == count($values_ar)) {
                for ($i = 0; $i < count($values_en); $i ++) {
                    MultiOptionValue::create([
                    'multi_option_id' => $option->id,
                    'value_en' => trim($values_en[$i], ' '),
                    'value_ar' => trim($values_ar[$i], ' ')
                    ]);
                }
            }
        }
        

        return redirect()->route('multi_options.index');
    }

    public function delete_m_option_value(MultiOptionValue $mvalue) {
        $mvalue->delete();

        return redirect()->back();
    }

    public function edit_m_option_value(MultiOptionValue $mvalue) {
        $data['option'] = $mvalue;

        return view('admin.m_option_value_edit', ['data' => $data]);
    }

    public function edit_m_option_value_post(Request $request, MultiOptionValue $mvalue) {
        $post = $request->all();
        $mvalue->update($post);

        return redirect()->route('multi_options.index');
    }

    // delete
    public function delete(MultiOption $option) {
        $option->categories()->sync([]);
        $option->values()->delete();
        $option->delete();

        return redirect()->back();
    }
}