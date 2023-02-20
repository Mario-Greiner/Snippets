<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once(dirname(__FILE__).'/datamatrix.php');
    $barcode_array = array();

    function getBarcodeSVGcode($w=3, $h=3, $color='black', $barcode_array) {
		// replace table for special characters
		$repstr = array("\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;');
		$svg = '<'.'?'.'xml version="1.0" standalone="no"'.'?'.'>'."\n";
		$svg .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'."\n";
		$svg .= '<svg width="'.round(($barcode_array['num_cols'] * $w), 3).'" height="'.round(($barcode_array['num_rows'] * $h), 3).'" version="1.1" xmlns="http://www.w3.org/2000/svg">'."\n";
		$svg .= "\t".'<desc>'.strtr($barcode_array['code'], $repstr).'</desc>'."\n";
		$svg .= "\t".'<g id="elements" fill="'.$color.'" stroke="none">'."\n";
		// print barcode elements
		$y = 0;
		// for each row
		for ($r = 0; $r < $barcode_array['num_rows']; ++$r) {
			$x = 0;
			// for each column
			for ($c = 0; $c < $barcode_array['num_cols']; ++$c) {
				if ($barcode_array['bcode'][$r][$c] == 1) {
					// draw a single barcode cell
					$svg .= "\t\t".'<rect x="'.$x.'" y="'.$y.'" width="'.$w.'" height="'.$h.'" />'."\n";
				}
				$x += $w;
			}
			$y += $h;
		}
		$svg .= "\t".'</g>'."\n";
		$svg .= '</svg>'."\n";
		return $svg;
	}

    function getBarcodeSVG($w=3, $h=3, $color='black', $barcode_array) {
		// send headers
		$code = getBarcodeSVGcode($w, $h, $color, $barcode_array);
		header('Content-Type: application/svg+xml');
		header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Content-Disposition: inline; filename="'.md5($code).'.svg";');
		//header('Content-Length: '.strlen($code));
		echo $code;
	}


    $code = 'Max Mustermann';

	$qrcode = new Datamatrix($code);
	$barcode_array = $qrcode->getBarcodeArray();
	$barcode_array['code'] = $code;
    
    // output the barcode as SVG image
    getBarcodeSVG(15, 15, 'green', $barcode_array);

?>