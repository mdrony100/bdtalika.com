<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gallery;
use App\Models\Listing;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function show()
    {
        $data[0] = 0;
        $id = $_GET['id'];
        $listing = Listing::findOrFail($id);
        if(count($listing->galleries))
        {
            $data[0] = 1;
            $data[1] = $listing->galleries;
        }
        return response()->json($data);
    }

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
                        if (in_array($val, ['jpeg', 'jpg', 'png', 'svg'])) {
                            $gallery = new Gallery();
                            $name = time() . $file->getClientOriginalName();
                            $webpName = pathinfo($name, PATHINFO_FILENAME) . '.webp';
                            $webpPath = 'assets/images/' . $webpName;
                    
                            // Save the image without resizing
                            $file->move('assets/images/', $webpName);
                    
                            // Add watermark to the image
                            $imagePath = 'assets/images/' . $webpName;
                            $watermark = 'assets/images/watermark.png';
                            $watermark_image = Image::make($watermark);
                    
                            // Calculate watermark size based on the image dimensions
                            $image = Image::make($imagePath);
                            $watermarkSize = round(10 * $image->width() / 50);
                            $watermark_image->resize($watermarkSize, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                    
                            // Insert the watermark into the image
                            $watermarkedImage = $image->insert($watermark_image, 'bottom-right', 10, 10)->save();
                    
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
        @unlink('assets/images/'.$gal->photo);
        $gal->delete();
    }
}
