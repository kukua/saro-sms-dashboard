<?php
$static_url = base_url()."assets/backend/mosaico/uploads/static/".$this->session->userdata('id')."/";

$uploads_dir = FCPATH."assets".DS."backend".DS."mosaico".DS."uploads".DS.$this->session->userdata('id').DS;
$static_dir = FCPATH."assets".DS."backend".DS."mosaico".DS."uploads".DS."static".DS.$this->session->userdata('id').DS;
$thumbnails_dir = FCPATH."assets".DS."backend".DS."mosaico".DS."uploads".DS."thumbnails".DS.$this->session->userdata('id').DS;
$thumbnail_width = 90;
$thumbnail_height = 90;

$image = new Imagick( $uploads_dir . $file_name );

if ( $method == "resize" )
{
	$image->resizeImage( $width, $height, Imagick::FILTER_LANCZOS, 1);
}
else // $method == "cover"
{
	$image_geometry = $image->getImageGeometry();

	$width_ratio = $image_geometry[ "width" ] / $width;
	$height_ratio = $image_geometry[ "height" ] / $height;

	$resize_width = $width;
	$resize_height = $height;

	if ( $width_ratio > $height_ratio )
	{
		$resize_width = 0;
	}
	else
	{
		$resize_height = 0;
	}

	$image->resizeImage( $resize_width, $resize_height, Imagick::FILTER_LANCZOS, 1 );

	$image_geometry = $image->getImageGeometry();

	$x = ( $image_geometry[ "width" ] - $width ) / 2;
	$y = ( $image_geometry[ "height" ] - $height ) / 2;

	$image->cropImage( $width, $height, $x, $y );
}
