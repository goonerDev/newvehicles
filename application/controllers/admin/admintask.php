<?php
/**
 * Admin task is related to actions like import, export
 */
require_once 'home.php';
abstract class Admintask extends Home {

	protected $_is_admin;
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

		$this->load->model( 'admindao' );

		$this->_bot = @$_POST[ 'passphrase' ] == 'x9a^p$%*32./a=)@sWdxf[[[&Dw<||<.';
	}

	/**
	 * @params array $param file - dao - import_type (rebuild|update)
	 */
	function import( $params ) {
		extract( $params );

		$paths = self::_get_absolute_paths( $format, $source, $file );

		// Is there any file to import?
		$real_path_wildedcars = glob( $paths[ 'realname' ] );
		if( empty( $real_path_wildedcars ) ) {
			// parent::mail( array(
				// 'recipient' => 'hello@malblackshaw.com',
				// 'from' => 'no-reply@'.$this->store_id,
				// 'subject' => 'Importing data',
				// 'msg' => '<strong>'.$paths[ 'realname' ].'</strong>: '.E_FILE_MISSING
			// ) );

			die( '{"err":"'.E_FILE_MISSING.'"}' );
		}

		$file_check = md5( file_get_contents( $real_path_wildedcars[ 0 ] ) );
		if( $file_check != str_replace( '.'.$format, '', substr( $real_path_wildedcars[ 0 ], strpos( $real_path_wildedcars[ 0 ], '+' ) + 1 ) ) )
			die( '{"err":"'.E_FILE_MISSING.'"}' );

		$this->admindao = $dao;

		$this->admindao->backup( $import_type, $this->_bot );
		$this->parse_and_insert( $real_path_wildedcars[ 0 ], $format );
		$this->admindao->drop_backup( $import_type );
		$this->admindao->additional_treatment( $import_type );

		rename($real_path_wildedcars[ 0 ], $paths[ 'complete_p' ] );
	}
	
	
	/**
	 *
	 */
	function parse_and_insert( $filename, $format ) {
		if( $format == 'csv' )
			$this->_parse_csv( $filename ); // _parse_csv is defined in subclass
		elseif( $format == 'xml' )
			$this->_parse_xml( $filename );
	}

	/**
	 *
	 */
	static function _get_absolute_paths( $format, $source, $file ) {
		if( $format == 'csv' ) {
			$realname = READ_IMPORTCSV_PATH.'/'.$file.'.csv';
			// Remove the wildcard
			$complete_p = COMPLETE_IMPORTCSV_PATH.'/'.str_replace( '*', '', $file ).date( 'Y-m-d H-i-s' ).'.csv';
		}
		elseif( $format == 'xml' ) {
			$realname = READ_IMPORTXML_PATH.'/'.$file.'.xml';
			// Remove the wildcard
			$complete_p = COMPLETE_IMPORTXML_PATH.'/'.str_replace( '*', '', $file ).date( 'Y-m-d H-i-s' ).'.xml';
		}
		else
			die( '{"err":"'.E_UNKNOWN_IMPORT_FORMAT.'"}' );

		if( $source == 'remote' )
			move_uploaded_file( $_FILES[ 'file' ][ 'tmp_name' ], $realname );

		return array( 'realname' => $realname, 'complete_p' => $complete_p );
	}

	/**
	 *
	 */
	function _parse_csv( $filename ) {
		$this->admindao->_insert_csv( $filename );
	}

	/**
	 *
	 */
	function _parse_xml( $filename ) {
		$parser = xml_parser_create();
		xml_set_element_handler( $parser, array( &$this, '_start_tag' ), array( &$this, '_end_tag' ) );
		xml_set_character_data_handler( $parser, array( &$this, '_data_xml' ) );

		$f = fopen( $filename, 'r' );

		while( $line = fread( $f, 4096 ) )
			if( 0 == xml_parse( $parser, $line ) )
				die( '{"err":"'.misc::esc_json( xml_error_string( xml_get_error_code( $parser ) ) ).'"}' );

		xml_parser_free( $parser );

		fclose( $f );
	}

	/**
	 *
	 */
	function _start_tag( $parser, $name, $attribs ) {
		$this->_cur_tag = strtolower( $name );
	}

	/**
	 *
	 */
	function _end_tag( $parser, $name ) {}
	function _data( $parser, $data ) {}

	/**
	 *
	 */
	function download_exported_if_needed( $name, $content ) {
		if( !empty( $_POST[ 'export_output' ] ) && $_POST[ 'export_output' ] == 'download' )
			parent::download( $name, $content );
	}

	/**
	 *
	 */
	function save_exported_file( $filename, $content ) {
		$f = fopen( $filename, 'w' );
		fputs( $f, $content );
		fclose( $f );
	}
}