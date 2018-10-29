<?php

namespace photo_story;

require_once 'util.php';

/** A collection of images with text positioned on them */
class Story {
	function __construct( $story_name ) {
		$this -> story_name = $story_name;
		$this -> images = [];
	}

	public function add_image( $story_image ) {
		array_push( $this -> images, $story_image );
	}

	/** Render the story as HTML at the site of invocation */
	function render( $language ) {
		foreach( $this -> images as $image ) {
			$image -> render( $language );
		}
	}
}

class StoryImage {
	public function __construct( $label, $image_url ) {
		$this -> image_url = $image_url;
		$this -> label = $label;
		$this -> text_blocks = [];
		list( $width, $height ) = getimagesize( $image_url );
		$this -> width = $width;
		$this -> height = $height;
	}

	public function set_text( $language, $text_blocks ) {
		$this -> text_blocks[ $language ] = $text_blocks;
	}

	public function render( $language ) {
		$story_text_blocks = $this -> text_blocks[ $language ];
		echo '<div class="storyimage" id="',
			$this -> label,
			'" style="position: relative; background-image: url(',
			$this -> image_url,
			'); height: ',
			$this -> height,
			'px; width: ',
			$this -> width,
			'px">';

		foreach( $story_text_blocks as $text_block ) {
			// this could really be more pretty...
			echo '<div style="position: absolute; top: ',
			     $text_block -> y,
			     'px; left: ',
			     $text_block -> x,
			     'px; color: ',
			     $text_block -> color,
			     '">',
			     $text_block -> text,
			     '</div>';
		}
		echo '</div>';
	}
}

/** Data class for storing text snippets that are meant to be displayed on images */
class TextBlock {
	public function __construct( $x, $y, $text, $color ) {
		$this -> text = $text;
		$this -> x = $x;
		$this -> y = $y;
		$this -> color = $color;
	}
}



/** Read image story data from filename and create an object of type Story */
function load_with_language( $story_name, $language ) {
	if( !\util\is_alphanum( $story_name ) || !\util\is_alphanum( $language ) ) {
		error_log( "User provided invalid story parameters: story_name=$story_name & language=$language" );
		return null;
	}

	$filename = "stories/${story_name}.${language}.txt";
	if( !file_exists( $filename ) ) {
		error_log( "Non-existing story definition file: $filename" );
		return null;
	}

	$result = new Story( $story_name );

	$story_image = null;
	$text_blocks = null;

	/* Definiton of file format:
	   Empty lines are ignored
	   Any line starting with a @ marks the beginning of a new image in a story.
	   Lines can be of two types: Image definition, textblock definition:
	   @	image_label	image_url
	   x	y	text block 1a
	   x	y	text block 1b
	   @	next_image_label	image_url
	   x	y	text block 2a
	*/
	foreach( file( $filename ) as $line ) {
		if( $line == "" ) {
			continue;
		}

		$tokens = explode( "\t", $line );

		if( $tokens[ 0 ] == "@" ) { //TODO also recognize files that start with a BOM
			if( $story_image !== null ) { // finish off previous image (if there is a previous image)
				$story_image -> set_text( $language, $text_blocks );
				$result -> add_image( $story_image );
			}

			$image_label = trim( $tokens[ 1 ] );
			$image_url = trim( $tokens[ 2 ] );

			$story_image = new StoryImage( $image_label, $image_url );
			$text_blocks = [];

		} else {

			$x = $tokens[ 0 ];
			$y = $tokens[ 1 ];
			$text = $tokens[ 2 ];
			$color = 'black';
			if( count( $tokens ) > 3 ) {
				$color = trim( $tokens[ 3 ] );
			}

			assert( $text_blocks !== null, "Input file does not start with a @ line" );
			array_push( $text_blocks,
			            new TextBlock( $x, $y, $text, $color ) );
		}
	}
	if( $story_image !== null ) { // finish off last image
		$story_image -> set_text( $language, $text_blocks );
		$result -> add_image( $story_image );
	}

	return $result;
}

?>
