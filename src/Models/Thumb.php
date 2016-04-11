<?php namespace Sanatorium\Thumbs\Models;

use Platform\Media\Models\Media as PlatformMedia;

class Thumb extends PlatformMedia {
	
	public function getUrlAttribute()
	{
		return route('thumb.view', $this->path);
	}

}