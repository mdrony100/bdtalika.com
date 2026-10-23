<?php

namespace App\Http\Controllers\Admin;

use App\Models\Generalsetting;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralSettingRequest;
use Illuminate\Support\Facades\Validator;
use Purifier;

class GeneralSettingController extends Controller
{

    protected $rules =
    [];

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function generalupdate(GeneralSettingRequest $request)
    {
        $input = $request->all();
        $data = Generalsetting::findOrFail(1);
        $prev = $data->time_zone;

        if (isset($input['menu'])) {
            $input['menu'] =  $this->setMenu($input);
        }

        if ($file = $request->file('logo')) {
            $name = fileName($file);
            upload($name, $file, $data->logo);
            $input['logo'] = $name;
        }

        if ($file = $request->file('og_image')) {
            $name = fileName($file);
            upload($name, $file, $data->og_image);
            $input['og_image'] = $name;
        }

        if ($file = $request->file('favicon')) {
            $name = fileName($file);
            upload($name, $file, $data->favicon);
            $input['favicon'] = $name;
        }
        if ($file = $request->file('loader')) {
            $name = fileName($file);
            upload($name, $file, $data->loader);
            $input['loader'] = $name;
        }
        if ($file = $request->file('admin_loader')) {
            $name = fileName($file);
            upload($name, $file, $data->admin_loader);
            $input['admin_loader'] = $name;
        }

        if ($file = $request->file('error_photo')) {
            $name = fileName($file);
            upload($name, $file, $data->error_photo);
            $input['error_photo'] = $name;
        }

        if ($file = $request->file('breadcumb_banner')) {
            $name = fileName($file);
            upload($name, $file, $data->breadcumb_banner);
            $input['breadcumb_banner'] = $name;
        }

        if ($file = $request->file('light_logo')) {
            $name = fileName($file);
            upload($name, $file, $data->light_logo);
            $input['light_logo'] = $name;
        }

        if ($file = $request->file('footer_logo')) {
            $name = fileName($file);
            upload($name, $file, $data->footer_logo);
            $input['footer_logo'] = $name;
        }

        if ($request->copyright) {
            $input['copyright'] = Purifier::clean($request->copyright);
        }

        if ($request->maintain_text) {
            $input['maintain_text'] = Purifier::clean($request->maintain_text);
        }

        if ($request->nexmo_status == 1) {
            $input['nexmo_status'] = 1;
            $input['twilio_status'] = 0;
        } else {
            $input['nexmo_status'] = 0;
            $input['twilio_status'] = 1;
        }

        if ($request->twilio_status == 1) {
            $input['twilio_status'] = 1;
            $input['nexmo_status'] = 0;
        } else {
            $input['twilio_status'] = 0;
            $input['nexmo_status'] = 1;
        }


        // if ($file = $request->file('banner_image')) {
        //     $name = fileName($file);
        //     upload($name, $file, $data->banner_image);
        //     $input['banner_image'] = $name;
        // }
        // $input['show_banner'] = $request->show_banner;
        // $input['banner_url']  = $request->banner_url;

        $input['app_mode'] = $request->has('app_mode') ? 1 : 0;
        $input['is_active_ai_assistant'] = $request->has('is_active_ai_assistant') ? 1 : 0;
        $this->emailConfig($input);
        $data->update($input);

        try {
            $this->setEnv('APP_TIMEZONE', $input['time_zone']);
        } catch (\Throwable $th) {
        }

        if ($request->ajax()) {
            $msg = 'Data Updated Successfully.';
            return response()->json($msg);
        } else {
            return back()->withMessage('Data Updated Successfully.');
        }
    }


    public function setMenu($input)
    {
        unset($input['menu']);
        unset($input['_token']);
        return json_encode($input);
    }

    public function logo()
    {
        return view('admin.generalsetting.logo');
    }

    public function breadcumb()
    {
        return view('admin.generalsetting.breadcumb');
    }

    public function theme()
    {
        return view('admin.generalsetting.theme');
    }

    public function fav()
    {
        return view('admin.generalsetting.favicon');
    }

    public function load()
    {
        return view('admin.generalsetting.loader');
    }

    public function contents()
    {
        return view('admin.generalsetting.websitecontent');
    }


    public function footer()
    {
        return view('admin.generalsetting.footer');
    }

    public function menubuilder()
    {
        return view('admin.menubuilder.index');
    }

    public function cookie()
    {
        return view('admin.generalsetting.cookie');
    }

    public function customcss()
    {
        $file = 'assets/front/css/custom.css';

        if (!file_exists($file)) {
            fopen($file, "w+")
                or die("Unable to open file!");
        }
        $data = file_get_contents($file);

        return view('admin.generalsetting.css', compact('data'));
    }

    public function customcssSubmit(Request $request)
    {
        $file = 'assets/front/css/custom.css';

        if (!file_exists($file)) {
            fopen($file, "w+")
                or die("Unable to open file!");
        }

        file_put_contents($file, $request->custom_css);

        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    public function holiday()
    {
        $data = Generalsetting::first();
        return view('admin.generalsetting.holiday', compact('data'));
    }

    public function holidayUpdate(Request $request)
    {
        $data = Generalsetting::find(1);

        if ($request->day_of) {
            $input['day_of'] = implode(" , ", $request->day_of);
        } else {
            $input['day_of'] = NULL;
        }
        $data->update($input);

        if ($request->ajax()) {
            $msg = 'Data Updated Successfully.';
            return response()->json($msg);
        } else {
            return back()->withSuccess('Data Updated Successfully.');
        }
    }

    public function paymentsinfo()
    {
        $curr = Currency::where('is_default', '=', 1)->first();
        return view('admin.generalsetting.paymentsinfo', compact('curr'));
    }

    public function affilate()
    {
        return view('admin.generalsetting.affilate');
    }

    public function errorbanner()
    {
        return view('admin.generalsetting.error_banner');
    }

    public function maintain()
    {
        return view('admin.generalsetting.maintain');
    }

    public function popupAlert()
    {
        return view('admin.generalsetting.popup_alert');
    }

    public function twilio()
    {
        return view('admin.generalsetting.twilio');
    }

    public function nexmo()
    {
        return view('admin.generalsetting.nexmo');
    }

    public function status($field, $value)
    {
        $prev = '';
        $data = Generalsetting::find(1);
        if ($field == 'is_debug') {
            $prev = $data->is_debug == 1 ? 'true' : 'false';
        }
        $data[$field] = $value;
        $data->update();
        if ($field == 'is_debug') {
            $now = $data->is_debug == 1 ? 'true' : 'false';
            $this->setEnv('APP_DEBUG', $now, $prev);
        }

        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
    }

    public function emailConfig($input)
    {

        try {
            $this->setEnv('MAIL_HOST', $input['smtp_host']);
            $this->setEnv('MAIL_PORT', $input['smtp_port']);
            $this->setEnv('MAIL_USERNAME', $input['smtp_user']);
            $this->setEnv('MAIL_PASSWORD', $input['smtp_pass']);
            $this->setEnv('MAIL_ENCRYPTION', 'tls');
            if (isset($input['molly_key'])) {
                $this->setEnv('MOLLIE_KEY', $input['molly_key']);
            }
        } catch (\Throwable $e) {
        }
    }

    private function setEnv($key, $value)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . env($key),
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }
}
