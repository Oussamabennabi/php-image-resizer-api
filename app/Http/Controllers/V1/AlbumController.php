<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Http\Requests\StoreAlbumRequest;
use App\Http\Requests\UpdateAlbumRequest;
use Illuminate\Http\Response;
use App\resources\V1\AlbumResource;
class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // you can replace all with paginate
        return AlbumResource::collection(Album::all());
      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlbumRequest $request)
    {
        
        $album = new Album($request->all());
        $album->save();
        return new AlbumResource($album);

    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        return new AlbumResource($album);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlbumRequest $request, Album $album)
    {
        $album->update($request->all());
        return new AlbumResource($album);

        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        $album->delete();
        return response('album deleted successfuly!',204);
    }
}
