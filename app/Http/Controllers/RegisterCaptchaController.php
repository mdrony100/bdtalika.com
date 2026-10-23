<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterCaptchaController
{

    public static $min = 1;
    public static $max = 9;

    static function generate()
    {
        $num1 = rand(self::$min, self::$max);
        $num2 = rand(self::$min, self::$max);

        $opr_list = ['+'];

        $opt = $opr_list[array_rand($opr_list, 1)];

        session()->put('num_1', $num1);
        session()->put('num_2', $num2);
        session()->put('num_opt', $opt);
    }

    static function reset()
    {
        self::generate();
    }

    static function get()
    {
        return [
            'num_1' => session('num_1'),
            'num_2' => session('num_2'),
            'num_opt' => session('num_opt'),
        ];
    }

    static function genGet()
    {
        self::generate();
        return self::get();
    }
    /**
     * captcha input
     *
     * @param Request $request
     * @param string $name captcha input field
     * @param string $target captcha parent element
     * @return void
     */
    static function input($name = "captchafield", $target = "")
    {
        $data =  self::genGet();
        return '<div id="captcha-wrapper" class="input-group"><label for="captcha" class="form-label d-block w-100 captcha-label">Please solve the captcha: <span class="text-danger">*</span> ' . $data['num_1'] . ' ' . $data['num_opt'] . ' ' . $data['num_2'] . '</label>
            <input type="number" id="captcha_field" name="' . $name . '" required="required" value="" placeholder="Please solve the captcha" class="form-control py-2 rounded"></div>
        ';
    }
    /**
     * captcha input refresh
     *
     * @param Request $request
     * @param string $name captcha input field
     * @return void
     */
    static function inputRefresh($name = "captchafield")
    {
        self::generate();
        return self::input($name = "captchafield");
    }
    /**
     * captcha validation
     *
     * @param Request $request
     * @param string $name captcha input field
     * @return void
     */
    static function validate(Request $request, $name = "captchafield") {
        $validator = Validator::make($request->all(), [
            $name => 'required|numeric'
        ], [
            $name . '.required' => 'Captcha Field is required',
            $name . '.numeric'  => 'The captcha must be a number.'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $data = self::get();
        if (((int) $request->get($name)) != ($data['num_1'] + $data['num_2'])) {
            $validator->errors()->add($name, 'Invalid captcha, please try again');
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        return true;
    }
    

    function resetCaptcha(Request $request)
    {
        $data = self::input($request->name, $request->target);
        return $data;
    }
    /**
     * Undocumented getScript
     *
     * @param string $selector to where new html code with input will be loaded
     * @return void
     */
    static function getScript($selector = '#captcha_box')
    {
        return "$(document).on('click', '.captcha-refresh-btn', function(event) {
            if($(this).data('target')){
                var selector = $(this).data('target')
            }else{
                var selector = '" . $selector . "'
            }
            var name = $(selector).find('input').prop('name')
            event.preventDefault();
            /* Act on the event */
            $.ajax({
                data: {name: name,target:selector},
                url: '" . route('front.captcha') . "',
            })
            .done(function(e) {
                $(selector).html(e);
            })
        });";
    }
}
