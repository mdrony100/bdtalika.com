<?php

namespace App\Http\Controllers\User;

use App\Models\Gallery;
use App\Models\Listing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $data[0] = 0;
        $id = $_GET['id'];
        $listing = Listing::findOrFail($id);
        if (count($listing->galleries)) {
            $data[0] = 1;
            $data[1] = $listing->galleries;
        }
        return response()->json($data);
    }

    // public function store(Request $request)
    // {
    //     $data = null;
    //     $lastid = $request->listing_id;
    //     if ($files = $request->file('gallery')){
    //         foreach ($files as  $key => $file){
    //             $val = $file->getClientOriginalExtension();
    //             if($val == 'jpeg'|| $val == 'jpg'|| $val == 'png'|| $val == 'svg')
    //               {
    //                 $gallery = new Gallery();
    //                 $name = time().$file->getClientOriginalName();
    //                 $file->move('assets/images',$name);
    //                 $gallery['photo'] = $name;
    //                 $gallery['listing_id'] = $lastid;
    //                 $gallery->save();
    //                 $data[] = $gallery;
    //               }
    //         }
    //     }
    //     return response()->json($data);
    // }

    public function store(Request $request)
    {
        $data = null;
        $lastid = $request->listing_id;
        if ($files = $request->file('gallery')) {
            foreach ($files as $key => $file) {
                $val = $file->getClientOriginalExtension();
                if ($files = $request->file('gallery')) {
                    foreach ($files as $key => $file) {
                        $val = $file->getClientOriginalExtension();
                        if ($val == 'jpeg' || $val == 'jpg' || $val == 'png' || $val == 'svg') {
                            $gallery = new Gallery();
                            $name = time() . $file->getClientOriginalName();
                            $webpName = pathinfo($name, PATHINFO_FILENAME) . '.webp';
                            $webpPath = 'assets/images/' . $webpName;
                            // Resize the image
                            $resizeWidth = 850;
                            $resizeHeight = 650;
                        
                            $imagePath = 'assets/images/' . $webpName;
                            $imageInstance = Image::make($file);
                            $imageInstance->fit($resizeWidth, $resizeHeight, function ($constraint) {
                                $constraint->upsize();
                            });
                            // Save the resized image on a canvas with a background color
                            $canvasColor = '#EBEEF7';
                            Image::canvas($resizeWidth, $resizeHeight, $canvasColor)
                                ->insert($imageInstance, 'center')
                                ->save($imagePath);
                            // Add watermark to the resized image
                            $watermark = 'assets/images/watermark.png';
                            $watermark_image = Image::make($watermark);
                        
                            // Calculate watermark size based on the resized image
                            $watermarkSize = round(10 * $resizeWidth / 50);
                            $watermark_image->resize($watermarkSize, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            // Insert the resized watermark into the final image
                            $watermarkedImage = Image::make($imagePath)
                                ->insert($watermark_image, 'bottom-right', 10, 10)
                                ->save($imagePath);
                        
                            // Save gallery details
                            $gallery['photo'] = $webpName;
                            $gallery['listing_id'] = $lastid;
                            $gallery->save();
                        
                            $data[] = $gallery;
                        }
                        
                    }
                }  
            }
        }             
        return response()->json($data);
    }


    public function destroy()
    {
        $id = $_GET['id'];
        $gal = Gallery::findOrFail($id);
        @unlink('assets/images/' . $gal->photo);
        $gal->delete();
    }
}
