<?php namespace Sanatorium\Thumbs\Traits;

use Platform\Media\Repositories\MediaRepositoryInterface;
use File;
use Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Platform\Media\Models\Media as MediaModel;

trait ThumbTrait {

	public function thumbs()
	{
		return $this->morphToMany('Sanatorium\Thumbs\Models\Thumb', 'entity', 'media_assign', 'entity_id', 'media_id');
	}

	public function coverThumb($w = 0, $h = 0, $url = true)
	{
		if ( !$this->has_cover_image )
			return $this->placeholder($w, $h, $url);
		

		$cover = $this->cover_image;

		if ( is_object($cover) )
			return $url ? $this->url($cover, $w, $h) : $cover;

		return $this->placeholder($w, $h, $url);
	}

	public function url($thumb, $w = 0, $h = 0)
	{
		return route('thumb.view', $thumb->path) . '?w=' . $w . '&h=' . $h;
	}

	public function placeholder($w = 0, $h = 0, $url = true)
	{
		$placeholder = new MediaModel([
			'path' => 'placeholder.png'
			]);

		return $this->url($placeholder, $w, $h);
	}

}