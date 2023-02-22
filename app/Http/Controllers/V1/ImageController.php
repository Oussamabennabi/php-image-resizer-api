<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use Illuminate\Http\Response;
use App\resources\V1\ImageResource;
class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      return ImageResource::collection(Image::paginate());
    }

    /** 
     * get image by album
     */
    public function byAlbum(Album $album)
    {
        $where = [
            "album_id"=>$album->id,
        ];
      return ImageResource::collection(Image::where($where)->paginate());

    }

    /**
     * Store a newly created resource in storage.
     */
    public function resize(StoreImageRequest $request)
    {
        // 'image'=> ["required"],
        //     // 50% | 50 | 50.5% | 50.5
        //     'w'=> ['required','regex:/^\d+(\.\d+)?%?$/'], 
        //     'h'=> ['required','regex:/^\d+(\.\d+)?%?$/'], 
        //     "album_id"=> "exists:\App\Modals\Album, id",
            $all = $request->all();

            /** @var UploadedFile|string $image */
            $image = $all['image'];
            unset($all['image']);
            $data = [
                "type"=> Image::TYPE_RESIZE,
                "data"=>json_encode($all),
                "user_id"=>null,
            ];

            if(isset($all["album_id"])){
                // TODO:
                $data["album_id"] = $all['album_id'];
            }

            $dir = "images/".Str::random()."/";
            $absolutePath = public_path($dir);
            File::makedirectory($absolutePath);


            if($image&&$image instanceof UploadedFile) {
                $data['name'] = $image->getClientOriginalName();
                $image->move($absolutePath,$data['name']);
                $originalPath = $absolutePath.$data['name'];

            } else {
                $data['image'] =pathinfo($dir,PATHINFO_BASENAME);
                $filename =pathinfo($dir,PATHINFO_FILENAME);
                $extension =pathinfo($dir,PATHINFO_EXTENSION);
                $originalPath = $absolutePath.$data['name'];

                copy($image,$originalPath);                 
            }
            
            $data['path'] = $dir.$data['name'];
            $w = $all['w'];
            $h = $all['h'];

            list($width,$height,$image) = $this->getNewImage($w,$h,$originalPath);

            $resizedImageName = $filename."-resized".$extension;

            $image->resize($width,$height)->save($absolutePath.$resizedImageName);
            $data["output_path"] = $dir.$resizedImageName;

            $image = new Image($data);
                    $image->save();
                    return new ImageResource($image);

    }
    private function getNewImage($w,$h,string $originalPath) {

        // composer require intervation/image
        $image = Image::make($originalPath);
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        if(str_ends_with($w,"%")) {
            $newWidth =(((float) str_replace("%",'',$w))*$originalWidth)/100;


        }else {
            $newWidth = (float)$w;

        }
        if(str_ends_with($h,"%")) {
            $newHeight =(((float) str_replace("%",'',$w))*$originalHeight)/100;

        }else {
            $newHeight = (float)$h;
        }
        echo "<pre>";
        var_dump($newWidth,$newHeight);
        echo "</pre>";
        exit;
        return [$newWidth,$newHeight,$image];
    }

    /**
     * Display the specified resource.
     */
    // TODO:  what ever you named th dynamic route exmp : {image} you passit here:
    public function show(Image $image)
    {

        return new ImageResource($image);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateImageRequest $request, Image $image)
    {
        $image->update($request->all());
        return new ImageResource($image);

        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        $image->delete();
        return response('Image deleted successfuly!',204);
    }
}
