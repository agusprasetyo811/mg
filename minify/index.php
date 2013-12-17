<?php 
/**
 * MINIFY GENERATOR ENGINE
 *
 * Minify Engine generator modified (agusprasetyo811@gmail.com)
 * This is to create minify style and js file
 * Big thanks to http://castlesblog.com/2010/august/14/php-javascript-css-minification for this tutorial, CSSMIN AND JSMIN
 *
 * @author OMAPS LABS Agus Prasetyo (agusprasetyo811@gmail.com)
 * @link http://omaps-lab.blogspot.com/p/meg.html, http://castlesblog.com/2010/august/14/php-javascript-css-minification
 * @filesource 	http://github.com/agusprasetyo811/mg
 * @version 1.0;
 *
 */


require_once 'engine/cssmin.php';
require_once 'engine/jsmin.php';

require_once 'config.php';
extract($config);

// Get js and style from url
$js = @$_GET['js'];
$style = @$_GET['style'];


if ($style != NULL) {
	// Explode style to array files
	$files = array_unique(explode(',', $style));

	if ($cache_enable == TRUE) {

		$modified = 0;
		foreach($files as $file) {
			$age = filemtime($file);
			if($age > $modified) {
				$modified = $age;
			}
		}
		$offset = $cache_offset;

		header('Expires: ' . gmdate ("D, d M Y H:i:s", time() + $offset) . ' GMT');

		if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $modified) {
			header("HTTP/1.0 304 Not Modified");
			header ('Cache-Control:');
		} else {
			header ('Cache-Control: max-age=' . $offset);
			header ('Content-type: text/css; charset=UTF-8');
			header ('Pragma:');
			header ("Last-Modified: ".gmdate("D, d M Y H:i:s", $modified )." GMT");
				
				
			$result = '';
			if (is_array($files)) {
				foreach($files as $file) {
					if (file_exists('../'.$file)) {
						$filters = array (
								"ImportImports"                 => false,
								"RemoveComments"                => true,
								"RemoveEmptyRulesets"           => true,
								"RemoveEmptyAtBlocks"           => true,
								"ConvertLevel3AtKeyframes"      => false,
								"ConvertLevel3Properties"       => false,
								"Variables"                     => true,
								"RemoveLastDelarationSemiColon" => true
						);
						$plugins = array(
								"Variables"                     => true,
								"ConvertFontWeight"             => false,
								"ConvertHslColors"              => false,
								"ConvertRgbColors"              => false,
								"ConvertNamedColors"            => false,
								"CompressColorValues"           => false,
								"CompressUnitValues"            => true,
								"CompressExpressionValues"      => false
						);
						$result .= CssMin::minify(file_get_contents('../'.$file), $filters, $plugins);
					} else {
						exit('');
					}
				}
			}
			echo $result;
				
		}

	} else {

		$result = '';
		if (is_array($files)) {
			foreach($files as $file) {
				if (file_exists('../'.$file)) {
					$filters = array (
							"ImportImports"                 => false,
							"RemoveComments"                => true,
							"RemoveEmptyRulesets"           => true,
							"RemoveEmptyAtBlocks"           => true,
							"ConvertLevel3AtKeyframes"      => false,
							"ConvertLevel3Properties"       => false,
							"Variables"                     => true,
							"RemoveLastDelarationSemiColon" => true
					);
					$plugins = array(
							"Variables"                     => true,
							"ConvertFontWeight"             => false,
							"ConvertHslColors"              => false,
							"ConvertRgbColors"              => false,
							"ConvertNamedColors"            => false,
							"CompressColorValues"           => false,
							"CompressUnitValues"            => true,
							"CompressExpressionValues"      => false
					);
					$result .= CssMin::minify(file_get_contents('../'.$file), $filters, $plugins);
				} else {
					exit('');
				}
			}
		}
		header ('Content-type: text/css; charset=UTF-8');
		echo $result;

	}

} else if ($js != NULL) {
	// Explode js to array files
	$files = array_unique(explode(',', $js));


	if ($cache_enable == TRUE) {
		$modified = 0;
		foreach($files as $file) {
			$age = filemtime($file);
			if($age > $modified) {
				$modified = $age;
			}
		}

		$offset = $cache_offset;
		header ('Expires: ' . gmdate ("D, d M Y H:i:s", time() + $offset) . ' GMT');

		if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $modified) {
			header("HTTP/1.0 304 Not Modified");
			header ('Cache-Control:');
		} else {
			header ('Cache-Control: max-age=' . $offset);
			header ('Content-type: text/javascript; charset=UTF-8');
			header ('Pragma:');
			header ("Last-Modified: ".gmdate("D, d M Y H:i:s", $modified )." GMT");
				
				
			$result = '';
			if (is_array($files)) {
				foreach($files as $file) {
					if (file_exists('../'.$file)) {
						$result .= JSMin::minify(file_get_contents('../'.$file));
					} else {
						exit();
					}
				}
			}
			echo $result;
				
		}
	} else {

		$result = '';
		if (is_array($files)) {
			foreach($files as $file) {
				if (file_exists('../'.$file)) {
					$result .= JSMin::minify(file_get_contents('../'.$file));
				} else {
					exit();
				}
			}
		}
		header ('Content-type: text/javascript; charset=UTF-8');
		echo $result;

	}

} else {
	exit();
}