<?php

namespace App\Http\Controllers\Admin;
use App\Models\Pagesetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Str;
use Validator;
use Purifier;

class PageSettingController extends Controller
{
    public $ps;
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->ps = Pagesetting::findOrFail(1);
    }

    public function update(Request $request)
    {
        $data = $this->ps;
        $input = $request->all();

        if ($file = $request->file('about_photo'))
        {
            $name = Str::random(8).time().'.'.$file->getClientOriginalExtension();
            $file->move('assets/images',$name);
            @unlink('assets/images/'.$data->about_photo);
            $input['about_photo'] = $name;
        }

        if ($file = $request->file('hero_photo'))
        {
            $name = Str::random(8).time().'.'.$file->getClientOriginalExtension();
            $file->move('assets/images',$name);
            @unlink('assets/images/'.$data->hero_photo);
            $input['hero_photo'] = $name;
        }

        if ($file = $request->file('process_photo'))
        {
            $name = Str::random(8).time().'.'.$file->getClientOriginalExtension();
            $file->move('assets/images',$name);
            @unlink('assets/images/'.$data->process_photo);
            $input['process_photo'] = $name;
        }

        if ($file = $request->file('mission_photo'))
        {
            $name = Str::random(8).time().'.'.$file->getClientOriginalExtension();
            $file->move('assets/images',$name);
            @unlink('assets/images/'.$data->mission_photo);
            $input['mission_photo'] = $name;
        }

        if ($file = $request->file('download_photo'))
        {
            $name = Str::random(8).time().'.'.$file->getClientOriginalExtension();
            $file->move('assets/images',$name);
            @unlink('assets/images/'.$data->download_photo);
            $input['download_photo'] = $name;
        }

        if ($file = $request->file('listing_photo'))
        {
            $name = Str::random(8).time().'.'.$file->getClientOriginalExtension();
            $file->move('assets/images',$name);
            @unlink('assets/images/'.$data->listing_photo);
            $input['listing_photo'] = $name;
        }

        if($request->plan_subtitle){
            $input['plan_subtitle'] = Purifier::clean($request->plan_subtitle);
        }

        if($request->blog_text){
            $input['blog_text'] = Purifier::clean($request->blog_text);
        }

        if($request->download_text){
            $input['download_text'] = Purifier::clean($request->download_text);
        }


        $data->update($input);
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }
    public function homeupdate(Request $request)
    {
        $data = $this->ps;
        $input = $request->all();

        $data->update($input);
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    public function customization(){
        $data = $this->ps;
        return view('admin.pagesetting.customization',compact('data'));
    }

    public function customizationUpdate(Request $request){
        $data = $this->ps;

        if($request->home_module){
            $input['home_module'] = json_encode($request->home_module,true);
        }else{
            $input['home_module'] = NULL;
        }
        $data->update($input);

        if($request->ajax()){
            $msg = 'Data Updated Successfully.';
            return response()->json($msg);
        }else{
            return back()->withSuccess('Data Updated Successfully.');
        }
    }

    public function hero(){
        $data = $this->ps;
        return view('admin.pagesetting.hero_section',compact('data'));
    }

    public function download(){
        $data = $this->ps;
        return view('admin.pagesetting.download',compact('data'));
    }

    public function about(){
        $data = $this->ps;
        return view('admin.pagesetting.about_section',compact('data'));
    }

    public function listing()
    {
        $data = $this->ps;
        return view('admin.pagesetting.listing',compact('data'));
    }

    public function calltoaction(){
        $data = $this->ps;
        return view('admin.pagesetting.call_section',compact('data'));
    }

    public function contact()
    {
        $data = Pagesetting::find(1);
        return view('admin.pagesetting.contact',compact('data'));
    }

    public function sectionHeading(){
        $data = $this->ps;
        return view('admin.pagesetting.sectionheading',compact('data'));
    }

    public function customize()
    {
        $data = $this->ps;
        return view('admin.pagesetting.customize',compact('data'));
    }

    public function blogsection()
    {
        $ps = $this->ps;
        return view('admin.pagesetting.blog_section',compact('ps'));
    }

    public function faqupdate($status)
    {
        $page = $this->ps;
        $page->f_status = $status;
        $page->update();
        Session::flash('success', 'FAQ Status Upated Successfully.');
        return redirect()->back();
    }

    public function contactup($status)
    {
        $page = $this->ps;
        $page->c_status = $status;
        $page->update();
        Session::flash('success', 'Contact Status Upated Successfully.');
        return redirect()->back();
    }

    public function contactupdate(Request $request)
    {
        $page = $this->ps;
        $input = $request->all();
        $page->update($input);
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }


}
