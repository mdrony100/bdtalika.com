<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatchCaptchaController{

    public static $min = 1;
    public static $max = 9;

    static function generate(){
        $num1 = rand(self::$min,self::$max);
        $num2 = rand(self::$min,self::$max);

        $opr_list = ['+'];

        $opt = $opr_list[array_rand($opr_list,1)];

        session()->put('cap_num1',$num1);
        session()->put('cap_num2',$num2);
        session()->put('cap_opt',$opt);
    }

    static function reset(){
        self::generate();
    }

    static function get() {
        return [
            'cap_num1' => session('cap_num1'),
            'cap_num2' => session('cap_num2'),
            'cap_opt' => session('cap_opt'),
        ];
    }

    static function genGet(){
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
    static function input($name="captcha",$target=""){
        $data =  self::genGet();
        return '<div id="captcha-wrapper" class="input-group"><label for="captcha" class="form-label d-block w-100 captcha-label">Please solve the captcha: <span class="text-danger">*</span> '.$data['cap_num1'].' '.$data['cap_opt'].' '.$data['cap_num2'].'</label>
            <input type="text" id="captcha" name="'.$name.'" required="required" value="" placeholder="Please solve the captcha" class="form-control"></div>
        ';

    //     return '<div id="captcha-wrapper" class="input-group"><label for="captcha" class="form-label d-block w-100 captcha-label">Please solve the captcha: <span class="text-danger">*</span> '.$data['cap_num1'].' '.$data['cap_opt'].' '.$data['cap_num2'].'</label>
    //     <input type="text" id="captcha" name="'.$name.'" required="required" value="" class="form-control"><button data-target="'.$target.'" type="button" class="captcha-refresh-btn input-group-text btn btn-primary"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-refresh"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg></button></div>
    // ';
    }
    /**
 * captcha input refresh
 *
 * @param Request $request
 * @param string $name captcha input field
 * @return void
 */
    static function inputRefresh($name="captcha"){
        self::generate();
        return self::input($name="captcha");
    }
/**
 * captcha validation
 *
 * @param Request $request
 * @param string $name captcha input field
 * @return void
 */
    static function validate(Request $request,$name="captcha"){
        $validator = Validator::make($request->all(),[
            $name => 'required|numeric'
        ],[
            $name.'.required' => 'Captcha Field is required',
            $name.'.numeric'  => 'The captcha must be a number.'
        ]);
    
        $validator->validate();
        $data = self::get();
        if(((int) $request->get($name))!=($data['cap_num1'] + $data['cap_num2'])){
            
            $validator->errors()->add($name,'Invalid captcha, please try again'); 
            return back()->withInput()->withErrors($validator);
        }
        return true;
    }


    function resetCaptcha(Request $request){
        $data = self::input($request->name,$request->target);
        return $data;
    }
/**
 * Undocumented getScript
 *
 * @param string $selector to where new html code with input will be loaded
 * @return void
 */
    static function getScript($selector='#captcha-box'){
        return "$(document).on('click', '.captcha-refresh-btn', function(event) {
            
            if($(this).data('target')){
                var selector = $(this).data('target')
            }else{
                var selector = '".$selector."'
            }

            var name = $(selector).find('input').prop('name')
            event.preventDefault();
            /* Act on the event */
            $.ajax({
                data: {name: name,target:selector},
                url: '".route('front.captcha')."',
            })
            .done(function(e) {
                $(selector).html(e);
            })
            
        });";
    }
}
