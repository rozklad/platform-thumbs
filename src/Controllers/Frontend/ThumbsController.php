<?php namespace Sanatorium\Thumbs\Controllers\Frontend;

use Platform\Media\Controllers\Frontend\MediaController;
use File;
use Cartalyst\Filesystem\Laravel\Facades\Filesystem;
use Image;
use Platform\Media\Models\Media as MediaModel;

class ThumbsController extends MediaController {
	

    /**
     * Returns the given media file.
     *
     * @param  string  $path
     * @return \Illuminate\Http\Response
     */
    public function view($path)
    {
    	if ( $path != 'placeholder.png' ) {
        	$media = $this->getMedia($path);
    	} else {
    		$media = new MediaModel([
    			'mime' => 'image/png',
    			'path' => 'placeholder.png'
    			]);
    	}

        if ( request()->has('w') || request()->has('h') ) {
    		
    		$w = request()->has('w') ? request()->get('w') : 0;
    		$h = request()->has('h') ? request()->get('h') : 0;

    		return $this->viewSize($media, $w, $h);
    	}

        $file = Filesystem::read($media->path);

        $etag = md5($file);

        $ttl = (int) config('platform/media::ttl');

        $headers = [
            'ETag'           => $etag,
            'Content-Type'   => $media->mime,
            'Content-Length' => strlen($file),
            'Cache-Control'  => "max-age={$ttl}, public",
        ];

        if (request()->server('HTTP_IF_NONE_MATCH') === $etag) {
            return response(null, 304, $headers);
        }

        return $this->respond($file, $headers);
    }

    public function viewSize($media, $w = 0, $h = 0)
    {
    	$filepath = storage_path('files/thumbs/' . $w . 'x' . $h . '/' . $media->path);

        // Create thumbnail if not exists
    	if ( !File::exists($filepath) ) {

    		// If directory does not exist
    		$dir = dirname($filepath);

    		if ( !File::exists($dir) )
    			File::makeDirectory($dir, 0777, true);

    		$img = Image::make(storage_path('files/'.$media->path));

    		$canvas = Image::canvas($w, $h);

    		$img->resize($w, $h, function ($constraint) {
    			$constraint->aspectRatio();
			});

			$canvas->insert($img, 'center');

    		$canvas->save($filepath);

    	}

    	$file = file_get_contents($filepath);

    	$etag = md5($file);

        $ttl = (int) config('platform/media::ttl');
    	
    	$headers = [
            'ETag'           => $etag,
            'Content-Type'   => $media->mime,
            'Content-Length' => strlen($file),
            'Cache-Control'  => "max-age={$ttl}, public",
        ];

    	if (request()->server('HTTP_IF_NONE_MATCH') === $etag) {
            return response(null, 304, $headers);
        }

        return $this->respond($file, $headers);
    }

}