<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Slider;
use App\Ad;
use App\SliderAd;
use App\SlideradText;

class SliderController extends AdminController{
    // show sliders
    public function show() {
        $data['sliders'] = Slider::get();

        return view('admin.sliders', ['data' => $data]);
    }

    // add get
    public function AddGet() {
        $data['ads'] = Ad::where('place', 1)->get();
        

        return view('admin.slider_form', ['data' => $data]);
    }

    // add post
    public function AddPost(Request $request) {
        $post = $request->all();
        $slider = Slider::create(['type' => $post['type']]);
        if (count($post['ads']) > 0) {
            for ($i = 0; $i < count($post['ads']); $i ++) {
                SliderAd::create(['slider_id' => $slider['id'], 'ad_id' => $post['ads'][$i]]);
            }
        }

        return redirect()->route('sliders.show');
    }

    // Edit get
    public function EditGet(Slider $slider) {
        $data['ads'] = Ad::where('place', 1)->get();
        $data['slider_ads'] = SliderAd::where('slider_id', $slider->id)->pluck('ad_id')->toArray();
        $data['slider'] = $slider;
        $data['slider_bottom_ads'] = SliderAd::where('slider_id', 5)->pluck('ad_id')->toArray();
        $data['slider_categories_ads'] = SliderAd::where('slider_id', 6)->pluck('ad_id')->toArray();
        

        return view('admin.slider_edit', ['data' => $data]);
    }

    // Edit post
    public function EditPost(Request $request, Slider $slider) {
        $slider->ads()->sync($request->ads);

        return redirect()->back();
    }

    // details
    public function details(Slider $slider) {
        $data['slider'] = $slider->ads;

        return view('admin.slider_details', ['data' => $data]);
    }

    // get web slider
    public function getWebSlider() {
        $data = SlideradText::orderBy('id', 'desc')->get();

        return view('admin.web_sliders', compact('data'));
    }

    // get add web slider
    public function getAddWebSlider() {
        $data['ads'] = Ad::where('place', 1)->get();

        return view('admin.web_slider_form', ['data' => $data]);
    }

    // post add web slider
    public function postAddWebSlider(Request $request) {
        $data = $this->validate(\request(),
            [
                'ad_id' => 'required',
                'text1_en' => 'required',
                'text1_ar' => 'required',
                'text2_en' => 'required',
                'text2_ar' => 'required',
                'text3_en' => 'required',
                'text3_ar' => 'required',
                'price' => 'required',
                'highlighted' => 'nullable'
            ]);

        SlideradText::create($data);


        return redirect()->route('sliders.web.show')->with('success', trans('messages.added_s'));
    }

    // get edit web slider
    public function getEditWebSlider(SlideradText $slider) {
        $data['slider'] = $slider;
        $data['ads'] = Ad::where('place', 1)->get();

        return view('admin.web_slider_edit', ['data' => $data]);
    }

    // update web slider
    public function updateWebSlider(Request $request, SlideradText $slider) {
        $data = $this->validate(\request(),
        [
            'ad_id' => 'required',
            'text1_en' => 'required',
            'text1_ar' => 'required',
            'text2_en' => 'required',
            'text2_ar' => 'required',
            'text3_en' => 'required',
            'text3_ar' => 'required',
            'price' => 'required',
            'highlighted' => 'nullable'
        ]);

        $slider->update($data);

        return redirect()->route('sliders.web.show');
    }

    // get web slider details
    public function getWebSliderDetails(SlideradText $slider) {
        $data = $slider;

        return view('admin.web_slider_details', compact('data'));
    }
}