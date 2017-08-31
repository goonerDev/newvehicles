<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH."third_party/MX/Router.php";

class MY_Router extends MX_Router {


	function o_validate_request($segments)
	{
		if (count($segments) == 0)
		{
			return $segments;
		}

		// Does the requested controller exist in the root folder?
		if (file_exists(APPPATH.'controllers/'.$segments[0].'.php'))
		{
			return $segments;
		}

		// Is the controller in a sub-folder?
		if (is_dir(APPPATH.'controllers/'.$segments[0]))
		{
			// Set the directory and remove it from the segment array
			$this->set_directory($segments[0]);
			$segments = array_slice($segments, 1);

			if (count($segments) > 0)
			{
				// Does the requested controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].'.php'))
				{
					if ( ! empty($this->routes['404_override']))
					{
						$x = explode('/', $this->routes['404_override']);

						$this->set_directory('');
						$this->set_class($x[0]);
						$this->set_method(isset($x[1]) ? $x[1] : 'index');

						return $x;
					}
					else
					{
						show_404($this->fetch_directory().$segments[0]);
					}
				}
			}
			else
			{
				// Is the method being specified in the route?
				if (strpos($this->default_controller, '/') !== FALSE)
				{
					$x = explode('/', $this->default_controller);

					$this->set_class($x[0]);
					$this->set_method($x[1]);
				}
				else
				{
					$this->set_class($this->default_controller);
					$this->set_method('index');
				}

				// Does the default controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.'.php'))
				{
					$this->directory = '';
					return array();
				}

			}

			return $segments;
		}

		// If we've gotten this far it means that the URI does not correlate to a valid
		// controller class.  We will now see if there is an override
		if ( ! empty($this->routes['404_override']))
		{
			$x = explode('/', $this->routes['404_override']);

			$this->set_class($x[0]);
			$this->set_method(isset($x[1]) ? $x[1] : 'index');

			return $x;
		}

		return $segments;

		// Nothing else to do at this point but show a 404
		// show_404($segments[0]);
	}


	public function _validate_request($segments) {

		if (count($segments) == 0) return $segments;

		$segments = $this->o_validate_request( $segments );

		if ( !$this->locate( $segments ) ) {
			switch( count( $segments ) ) {
				case 1: // make
					array_unshift( $segments, 'catalog', 'models' );
					break;
				case 2: // make/model or make/ajaxCall or make-make_id/model-model_id (google ppc)
					if( $segments[ 1 ] == 'ajax' )
						array_unshift( $segments, 'catalog', 'models' );
					else
						array_unshift( $segments, 'catalog', 'start_end_year' );
					break;
				case 3: // make/ajaxCall/format or make/model/ajaxCall or make/model/model_type or make/model/cat-...
					if( $segments[ 1 ] == 'ajax' )
						array_unshift( $segments, 'catalog', 'models' );
					elseif( $segments[ 2 ] == 'ajax' || substr( $segments[ 2 ], 0, 4 ) == 'cat-' )
						array_unshift( $segments, 'catalog', 'start_end_year' );
					else
						array_unshift( $segments, 'catalog', 'products' );
					break;
				case 4: // make/model/ajaxCall/format or make/model/model_type/pattern or make/model/model_type/cat-... or make/model/model_type/sku or make/model/cat-.../start or make/model/cat-.../sides or make/model/cat-.../side_value or make/model/model_type/-
					if( $segments[ 2 ] == 'ajax' )
						array_unshift( $segments, 'catalog', 'start_end_year' );
					elseif( substr( $segments[ 2 ], 0, 4 ) == 'cat-' && $segments[ 3 ] == 'sides' ) {
						$segments[ 3 ] = false;
						array_unshift( $segments, 'catalog', 'sides' );
					}
					elseif( substr( $segments[ 2 ], 0, 4 ) == 'cat-' || substr( $segments[ 3 ], 0, 4 ) == 'cat-' || $segments[ 3 ] == 'certified' || $segments[ 3 ] == '-' )
						array_unshift( $segments, 'catalog', 'products' );
					else
						array_unshift( $segments, 'catalog', 'item' );
					break;
				case 5: // make/model/ajaxCall/format/pattern or make/model/model_type/pattern/start or make/model/model_type/cat-.../sides or make/model/model_type/cat-.../side_value
					if( $segments[ 2 ] == 'ajax' )
						array_unshift( $segments, 'catalog', 'start_end_year' );
					elseif( substr( $segments[ 3 ], 0, 4 ) == 'cat-' && $segments[ 4 ] == 'sides' ) {
						array_splice( $segments, 4, 1 );
						$category = $segments[ 3 ];
						$segments[ 3 ] = $segments[ 2 ];
						$segments[ 2 ] = $category;
						array_unshift( $segments, 'catalog', 'sides' );
					}
					elseif( substr( $segments[ 3 ], 0, 4 ) == 'cat-' )
						array_unshift( $segments, 'catalog', 'products' );
					else
						array_unshift( $segments, 'catalog', 'products' );
					break;
				case 6: // make/model/model_type/pattern/format/start
					array_unshift( $segments, 'catalog', 'products' );
					break;
				// case 7: // make/model/modeltype/cat-.../sides/ajax/html
					// array_splice( $segments, 4, 1 );
					// $category = $segments[ 3 ];
					// $segments[ 3 ] = $segments[ 2 ];
					// $segments[ 2 ] = $category;
					// array_unshift( $segments, 'fcatalog', 'sides' );
					// break;
			}

			$this->uri->segments = $segments;

			/* locate module controller */
			if ($located = $this->locate($segments)) return $located;
		}

		return $segments;

		/* use a default 404_override controller */
		// if (isset($this->routes['404_override']) AND $this->routes['404_override']) {
			// $segments = explode('/', $this->routes['404_override']);
			// if ($located = $this->locate($segments)) return $located;
		// }

		/* no controller found */
		// show_404();
	}
}