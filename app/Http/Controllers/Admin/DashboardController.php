<?php

namespace App\Http\Controllers\Admin;

use Zip;
use Carbon\Carbon;
use App\Models\Blog;
use App\Models\Plan;
use App\Models\User;
use App\Models\Order;
use App\Models\Invest;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Deposit;
use App\Models\Listing;
use App\Models\Currency;
use App\Models\Withdraw;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ListingReview;
use App\Models\ReferralBonus;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminUserConversation;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data['directories'] = Listing::get();
        $data['todayDirectories'] = Listing::whereDate('created_at', today())->count();
        $data['currentMonthDirectories'] = Listing::where('created_at', '>=', now()->startOfMonth())
    ->count();
       $data['currentMonthUsers'] = User::whereIsBanned(0)
    ->where('created_at', '>=', now()->startOfMonth())
    ->count();
        $data['bookings'] = Booking::get();
        $data['reviews'] = ListingReview::get();
        $data['blogs'] = Blog::all();
        $data['currency'] = Currency::whereIsDefault(1)->first();
        $data['transactions'] = Transaction::all();
        $data['plans'] = Plan::all();
        $data['tickets'] = AdminUserConversation::all();
        $data['acustomers'] = User::orderBy('id','desc')->whereIsBanned(0)->get();
        $data['todayCustomers'] = User::whereIsBanned(0)->whereDate('created_at', today())
    ->count();
        $data['bcustomers'] = User::orderBy('id','desc')->whereIsBanned(1)->get();
        $data['users'] = User::orderBy('id','desc')->limit(5)->get();

        $data['activation_notify'] = "";
        if (file_exists(public_path().'/rooted.txt')){
            $rooted = file_get_contents(public_path().'/rooted.txt');
            if ($rooted < date('Y-m-d', strtotime("+10 days"))){
                $activation_notify = "<i class='icofont-warning-alt icofont-4x'></i><br>Please activate your system.<br> If you do not activate your system now, it will be inactive on ".$rooted."!!<br><a href='".url('/admin/activation')."' class='btn btn-success'>Activate Now</a>";
            }
        }

        return view('admin.dashboard',$data);
    }
    public function passwordreset()
    {
        $data = Auth::guard('admin')->user();
        return view('admin.password',compact('data'));
    }

    public function changepass(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($request->cpass){
            if (Hash::check($request->cpass, $admin->password)){
                if ($request->newpass == $request->renewpass){
                    $input['password'] = Hash::make($request->newpass);
                }else{
                    return response()->json(array('errors' => [ 0 => 'Confirm password does not match.' ]));
                }
            }else{
                return response()->json(array('errors' => [ 0 => 'Current password Does not match.' ]));
            }
        }
        $admin->update($input);
        $msg = 'Successfully change your password';
        return response()->json($msg);
    }

    public function profile()
    {
        $data = Auth::guard('admin')->user();
        return view('admin.profile',compact('data'));
    }
    public function profileupdate(Request $request)
    {
        $rules =
        [
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'email' => 'unique:admins,email,'.Auth::guard('admin')->user()->id
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $input = $request->all();
        $input['hide_email'] = $request->input('hide_email', 0);
        $input['hide_phone'] = $request->input('hide_phone', 0);
        $data = Auth::guard('admin')->user();
            if ($file = $request->file('photo'))
            {
                $name = Str::random(8).time().'.'.$file->getClientOriginalExtension();
                $file->move('assets/images/',$name);
                if($data->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/'.$data->photo)) {
                        unlink(public_path().'/assets/images/'.$data->photo);
                    }
                }
            $input['photo'] = $name;
            }

            $input['slug'] = str_replace(" ","-",$input['name']);


        $data->update($input);
        $msg = 'Successfully updated your profile';
        return response()->json($msg);
    }

    public function generate_bkup()
    {
        $bkuplink = "";
        $chk = file_get_contents('backup.txt');
        if ($chk != ""){
            $bkuplink = url($chk);
        }
        return view('admin.movetoserver',compact('bkuplink','chk'));
    }

    public function clear_bkup()
    {
        $destination  = public_path().'/install';
        $bkuplink = "";
        $chk = file_get_contents('backup.txt');
        if ($chk != ""){
            unlink(public_path($chk));
        }

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }
        $handle = fopen('backup.txt','w+');
        fwrite($handle,"");
        fclose($handle);

        return redirect()->back()->with('success','Backup file Deleted Successfully!');
    }

    public function activation()
    {
        $activation_data = "";
        if (file_exists(public_path().'/project/license.txt')){
            $license = file_get_contents(public_path().'/project/license.txt');
            if ($license != ""){
                $activation_data = "<i style='color:darkgreen;' class='icofont-check-circled icofont-4x'></i><br><h3 style='color:darkgreen;'>Your System is Activated!</h3><br> Your License Key:  <b>".$license."</b>";
            }
        }
        return view('admin.activation',compact('activation_data'));
    }

    public function activation_submit(Request $request)
    {

        $purchase_code =  $request->pcode;
        $my_script =  'Listing Genius';
        $my_domain = url('/');

        $varUrl = str_replace (' ', '%20', config('services.genius.ocean').'purchase112662activate.php?code='.$purchase_code.'&domain='.$my_domain.'&script='.$my_script);

        if( ini_get('allow_url_fopen') ) {
            $contents = file_get_contents($varUrl);
        }else{
            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_URL, $varUrl);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            $contents = curl_exec($ch);
            curl_close($ch);
        }

        $chk = json_decode($contents,true);

        if($chk['status'] != "success")
        {

            $msg = $chk['message'];
            return response()->json($msg);

        }else{
            $this->setUp($chk['p2'],$chk['lData']);

            if (file_exists(public_path().'/rooted.txt')){
                unlink(public_path().'/rooted.txt');
            }

            $fpbt = fopen(public_path().'/project/license.txt', 'w');
            fwrite($fpbt, $purchase_code);
            fclose($fpbt);

            $msg = 'Congratulation!! Your System is successfully Activated.';
            return response()->json($msg);
        }
    }

    function setUp($mtFile,$goFileData){
        $fpa = fopen(public_path().$mtFile, 'w');
        fwrite($fpa, $goFileData);
        fclose($fpa);
    }

    public function movescript(){
        ini_set('max_execution_time', 3000);

        $destination  = public_path().'/install';
        $chk = file_get_contents('backup.txt');
        if ($chk != ""){
            unlink(public_path($chk));
        }

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }
        $src = base_path().'/vendor/update';
        $this->recurse_copy($src,$destination);
        $files = public_path();
        $bkupname = 'ListingGenius-By-GeniusOcean-'.date('Y-m-d').'.zip';
        $zip = Zip::create($bkupname)->add($files, true);
        $zip->close();

        $handle = fopen('backup.txt','w+');
        fwrite($handle,$bkupname);
        fclose($handle);

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }
        return response()->json(['status' => 'success','backupfile' => url($bkupname),'filename' => $bkupname],200);
    }

    public function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function getContacts(){
        $contacts = Contact::orderBy('id', 'desc')->get();
        return view('admin.contact.index', compact('contacts'));
    }
    
    public function viewContact($id){
        $contact = Contact::where('id', $id)->first();
        return view('admin.contact.view', compact('contact'));
    }

    public function searchKeywords(){
        $keywords = DB::table('search_keywords')->latest()->paginate(20);
        return view('admin.search_keyword', compact('keywords'));
    }
}
