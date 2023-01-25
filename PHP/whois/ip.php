<?php
	//Check if Domain is set
	if (isset($_GET['domain'])) {
		$ip = $_GET['domain'];
	} else {
		//Set fallback domain
		$ip = "greiner-mario.at";
	}

	function lookupIP($ip){
		$whoisservers = array(
			"whois.lacnic.net", // Latin America and Caribbean
			"whois.apnic.net", // Asia/Pacific
			"whois.arin.net", // North America
			"whois.ripe.net" // Europe, Middle East and Central Asia
		);

		$results = array();

		foreach ($whoisservers as $whoisserver) {
			$result = queryWhoisServer($whoisserver, $ip);
			if ($result && !in_array($result, $results)) {
				$results[$whoisserver] = $result;
			}
		}

		$res = "RESULTS FOUND: " . count($results);

		foreach ($results as $whoisserver => $result) {
			$res .= "\n\n-------------\nLookup results for " . $ip . " from " . $whoisserver . " server:\n\n" . $result;
		}

		return $res;
	}

	function validateIP($ip){
		$ipnums = explode(".", $ip);

		if (count($ipnums) != 4) {
			return false;
		}

		foreach ($ipnums as $ipnum) {
			if (!is_numeric($ipnum) || ($ipnum > 255)) {
				return false;
			}
		}

		return $ip;
	}

	function queryWhoisServer($whoisserver, $ip){
		$port = 43;
		$timeout = 10;
		$fp = @fsockopen($whoisserver, $port, $errno, $errstr, $timeout) or die("Socket Error " . $errno . " - " . $errstr);

		//whois.verisign-grs.com requires the equals sign ("=") or it returns any result containing the searched string.
		if($whoisserver == "whois.verisign-grs.com") $ip = "=".$ip;


		fputs($fp, $ip . "\r\n");
		$out = "";
		while (!feof($fp)) {
			$out .= fgets($fp);
		}

		fclose($fp);
		$res = "";
		if ((strpos(strtolower($out), "error") === FALSE) && (strpos(strtolower($out), "not allocated") === FALSE)) {
			$rows = explode("\n", $out);
			foreach ($rows as $row) {
				$row = trim($row);
				if (($row != '') && ($row[0] != '#') && ($row[0] != '%')) {
					$res .= $row . "\n";
				}
			}
		}

		return $res;
	}
?>
<!DOCTYPE html>
<html lang="de">

<head>
	<title>Whois | <?php echo $ip; ?></title>
    <style>
        html, body {
            margin: 0;
        }

        pre {
            margin: 50px;
            padding: 8px 20px;
            background-color: #E7E9EB;
            border-radius: 5px;
        }
    </style>
</head>

<body>
	<?php
		if ($ip) {
			$ip = trim($ip);

			if (validateIP($ip)) {
				$result = lookupIP($ip);
			} elseif (validateDomain($ip)) {
				$result = lookupDomain($ip);
			}
			echo '<pre><h1>Whois result:</h1>' . $result . '</pre>';

			if (validateIP($ip)) {
				echo '<pre><h1>Whois result:</h1>' . lookupIP($ip) . '</pre>';
			}else{
				echo '<h1>No information found for IP ' . $ip . '</h1>';
			}
		}
	?>

</body>

</html>