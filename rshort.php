<?php
	/*
		
		RShort - URL Shortener Script 1.0
		Copyright (c) 2010 Ruel Pagayon - http://ruel.me
		
		This program is free software: you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation, either version 3 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program.  If not, see <http://www.gnu.org/licenses/>.
	
	*/
	
	// Start CONFIG
	
	// DATABASE
	$dbserver 	= '';
	$dbuser 	= '';
	$dbpass 	= '';
	$dbname 	= '';
	
	// SITE NAME (With trailing forward slash)
	$sitename 	= '';
	
	// End CONFIG
	
	$mcon = mysql_connect($dbserver, $dbuser, $dbpass);
	if (!$mcon) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($dbname, $mcon);
	
	// Shortened URL to the original URL. Redirects something like: 'http://ruel.me/dC53' to: http://www.somelongdomain.com/long.php?long=somelongrequest 
	if (isset($_GET['hash'])) {
		$hash = $_GET['hash'];
		// Validate hash - the hash should only have alphanumeric characters with the length of 5. Uppercase and lowercase.
		if (preg_match('/^[a-zA-Z0-9]{5}$/', $hash)) {
			$result = mysql_query("SELECT * FROM rshort WHERE hash = '" . $hash . "'", $mcon);
			// Check if there's a result. Should only be one, because the `hash` field is unique.
			if (mysql_num_rows($result) == 1) {
				$data = mysql_fetch_assoc($result);
				mysql_close($mcon);
				// Redirect. Decode the URL
				header('Location: ' . urldecode($data['url']));
			} else {
				// Actually you can redirect this to your page if there's no result.
				header('HTTP/1.1 404 Not Found');
			}
		}
	}
	
	// Long URL to short URL. This outputs the short URL hash in raw text. Can be used with AJAX or some applications.
	if (isset($_POST['url'])) {
		// Validate URL
		if (is_url($_POST['url'])) {
			// Encode the URL (For safe MySQL input)
			$url = urlencode($_POST['url']);
			$result = mysql_query("SELECT * FROM rshort WHERE url = '" . $url . "'", $mcon);
			// Check if there's a result. Again, this should be only one (if any).
			if (mysql_num_rows($result) == 1) {
				$data = mysql_fetch_assoc($result);
				mysql_close($mcon);
				echo $sitename . $data['hash'];
				exit();
			}
			// No result, generate the hash
			// Check if the hash already exist in the database. If it does, generate another one.
			$existing_hash = true;
			while ($existing_hash) {
				$hash = gen_hash($url, 5);
				$result = mysql_query("SELECT * FROM rshort WHERE hash = '" . $hash . "'", $mcon);
				if (mysql_num_rows($result) == 0) {
					$existing_hash = false;
				}
			}
			// All is fine, let's insert the necessary values
			mysql_query("INSERT INTO rshort (url, hash) VALUES('" . $url . "', '" . $hash . "') ", $mcon) or die(mysql_error());
			// Then echo the hash.
			echo $sitename . $hash;
		} else {
			echo "Invalid URL";
		}
	}
	
	mysql_close($mcon);
	
	// Function to validate URL (I know abouth the filter_var but it works in PHP 5 >= 5.2 only.)
	function is_url($url) {
		// Credits for this regular expression is from: http://stackoverflow.com/questions/206059/php-validation-regex-for-url
		if (preg_match("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", $url))
			return true;
		else
			return false;
	}
	
	// Function to generate a random string.
	function gen_str($len) {
		if ($len < 1) {
			return 0;
		}
		// Writing an array this large is a pain :p
		$stsp = 'acefghjkpqrstwxyz23456789';
		$res = "";
		for ($i = 1; $i < $len; $i++)
			$res .= $stsp[mt_rand(0, 61)];
		return $res;
	}
	
	// Function to generate the hash. This hashing method is pretty unique, and random.
	function gen_hash($inp, $len) {
		if ($len < 1) {
			return 0;
		}
		$rsalt = gen_str(10);
		$thash = str_shuffle(md5(md5($url) . $rsalt . md5($rsalt)) . $rsalt);
		$hash = substr($thash, rand(0,31 - $len), $len);
		return $hash;
	}
?>