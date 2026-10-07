<?php

if( isset( $_POST[ 'Upload' ] ) ) {
	// File information
	$uploaded_name = isset( $_FILES[ 'uploaded' ][ 'name' ] ) ? $_FILES[ 'uploaded' ][ 'name' ] : '';
	$uploaded_ext  = strtolower( substr( $uploaded_name, strrpos( $uploaded_name, '.' ) + 1 ) );
	$uploaded_size = isset( $_FILES[ 'uploaded' ][ 'size' ] ) ? (int) $_FILES[ 'uploaded' ][ 'size' ] : 0;
	$uploaded_tmp  = isset( $_FILES[ 'uploaded' ][ 'tmp_name' ] ) ? $_FILES[ 'uploaded' ][ 'tmp_name' ] : '';

	// Where are we going to be writing to?
	$target_path = DVWA_WEB_PAGE_TO_ROOT . 'hackable/uploads/';

	// Hardened: the real content decides, not the client-supplied name or MIME type
	$image_info = ( $uploaded_tmp !== '' && is_uploaded_file( $uploaded_tmp ) ) ? @getimagesize( $uploaded_tmp ) : false;
	$mime = $image_info ? $image_info[ 'mime' ] : '';

	if( in_array( $uploaded_ext, array( 'jpg', 'jpeg', 'png' ), true ) &&
		( $uploaded_size > 0 && $uploaded_size < 100000 ) &&
		( $mime == 'image/jpeg' || $mime == 'image/png' ) ) {

		$new_ext     = ( $mime == 'image/jpeg' ) ? 'jpg' : 'png';
		$target_file = bin2hex( random_bytes( 16 ) ) . '.' . $new_ext;
		$destination = getcwd() . DIRECTORY_SEPARATOR . $target_path . $target_file;

		// Strip any metadata / embedded payloads by re-encoding the image
		$saved = false;
		if( $mime == 'image/jpeg' ) {
			$img = @imagecreatefromjpeg( $uploaded_tmp );
			if( $img ) { $saved = imagejpeg( $img, $destination, 100 ); imagedestroy( $img ); }
		}
		else {
			$img = @imagecreatefrompng( $uploaded_tmp );
			if( $img ) { $saved = imagepng( $img, $destination, 9 ); imagedestroy( $img ); }
		}

		if( $saved ) {
			$html .= "<pre><a href='{$target_path}{$target_file}'>{$target_file}</a> succesfully uploaded!</pre>";
		}
		else {
			$html .= '<pre>Your image was not uploaded.</pre>';
		}
	}
	else {
		// Invalid file
		$html .= '<pre>Your image was not uploaded. We can only accept JPEG or PNG images.</pre>';
	}
}

?>
