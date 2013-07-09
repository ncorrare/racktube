<html>
<head>
	<title> RackTube POC </title>
</head>
<?php
ini_set("upload_max_filesize", "100M"); 
	require ('php-opencloud.php');
	// Please populate the following variables:
	$cfuser=""; // CloudFiles username
	$cfapikey=""; // CloudFiles API Key
	$cfcontainer=""; // CloudFiles Container
	$cftempkey=""; // CloudFiles TempURL Key obtained from: curl -iH 'X-Auth-Token: TOKEN' https://storage101.lon3.clouddrive.com/v1/auth_ACCOUNT 
	$cfauthurl="https://lon.identity.api.rackspacecloud.com/v2.0/"; // Deprecated: Use https://lon.auth.api.rackspacecloud.com/ for UK, https://auth.api.rackspacecloud.com/ for US
	function format_bytes($a_bytes)
	{
    	if ($a_bytes < 1024) {
	        return $a_bytes .' B';
	    } elseif ($a_bytes < 1048576) {
	        return round($a_bytes / 1024, 2) .' KB';
	    } elseif ($a_bytes < 1073741824) {
	        return round($a_bytes / 1048576, 2) . ' MB';
	    } elseif ($a_bytes < 1099511627776) {
	        return round($a_bytes / 1073741824, 2) . ' GB';
	    } elseif ($a_bytes < 1125899906842624) {
	        return round($a_bytes / 1099511627776, 2) .' TB';
	    } elseif ($a_bytes < 1152921504606846976) {
	        return round($a_bytes / 1125899906842624, 2) .' PB';
	    } elseif ($a_bytes < 1180591620717411303424) {
	        return round($a_bytes / 1152921504606846976, 2) .' EB';
	    } elseif ($a_bytes < 1208925819614629174706176) {
	        return round($a_bytes / 1180591620717411303424, 2) .' ZB';
	    } else {
        	return round($a_bytes / 1208925819614629174706176, 2) .' YB';
    	}
	}
	function getmimetype($filename)
	{
		#$finfo = finfo_open(FILEINFO_MIME, "/usr/share/misc/magic"); // return mime type ala mimetype extension
		$finfo = finfo_open(FILEINFO_MIME); // return mime type ala mimetype extension

		if (!$finfo) {
		    echo "Opening fileinfo database failed";
		exit();
		}

		/* get mime-type for a specific file */
		return finfo_file($finfo, $filename);

		/* close connection */
		finfo_close($finfo);
	}


	$conn = new \OpenCloud\Rackspace(
		$cfauthurl,
    		array(
	        	'username' => $cfuser,
		        'apiKey' => $cfapikey,
		));	
	$ostore = $conn->ObjectStore('cloudFiles', 'LON');
	#$ostore = $conn->ObjectStore();
	$mycontainer = $ostore->Container($cfcontainer);

	if (isset($_POST["do"])) {
	$do=$_POST["do"];
	switch ($do)
		{
		case "upload":
			$target_path = "uploads/";

			$target_path = $target_path . basename( $_FILES['upfile']['name']); 
			echo $_FILES['upfile']['error'];
			if(move_uploaded_file($_FILES['upfile']['tmp_name'], $target_path)) {
		    		$filename=basename($_FILES['upfile']['name']);
				$desc=$_POST["desc"];
				$msg="The file ".$filename." has been uploaded";
				$myupload=$mycontainer->DataObject();
				$myupload->SetData(file_get_contents($target_path));
				$myupload->name = $filename;
				$mimetype=getmimetype($target_path);
				$myupload->content_type = $mimetype;
				$myupload->Create();
			} else{
		    		$msg="There was an error uploading the file ".$filename.", please try again!";
			}
		break;
		case "delete":
			$cfauth = new CF_Authentication($cfuser, $cfapikey, NULL, $cfauthurl);
                        $cfauth->authenticate();
                        $cfconn = new CF_Connection($cfauth);
                        $ccont = $cfconn->get_container($cfcontainer);
			$filename=$row["fname"];
			if ($ccont->delete_object($filename))
					{ 
					$msg="The file ".$filename." has been deleted";
					}
			break;

		}
	}
	if (isset($msg)) {
		echo "<body onLoad=\"javascript:alert('". $msg ."');\">\n"; 
	} else {
		echo "</body>\n";
	}
	?>
	<h1> Racktube </h1>
	<h2> This is a proof of concept! </h2>
	<h3> Upload a File... </h3>
	<form enctype="multipart/form-data" action="index.php" method="post">
	<input type="hidden" name="do" value="upload" />
	Choose a file: <input type="file" name="upfile" />
	Add a Description: <input name="desc" />

	<input type="submit" value="Upload to CDN">
	</form>
	<h3> Or download a previously uploaded file through the CDN </h3>
	<table border="1">
		<tr>
			<td>Name</td>
			<td>Size</td>
			<td>Stream</td>
			<td>Download</td>
		</tr>
		<?php
			$objlist = $mycontainer->ObjectList();
			while($object = $objlist->Next()) {
				echo "			<tr>\n";
				echo "				<td>$object->name </td>\n";
				$filesize = $object->bytes;
				$propersize=format_bytes($filesize);
				echo "				<td>".$propersize."</td>\n";
                                echo "<td><form method=\"POST\" action=\"SimplePlayer/index.php\"><input type=\"hidden\" name=\"url\" value=\"".$object->PublicURL('Streaming')."\"/><input type=\"hidden\" name=\"filename\" value=\"".$object->name."\"/><input type=\"submit\" name=\"Play\" value=\"Play\" /></form></td>\n"; 
				echo "				<td><a href=\"".$object->PublicUrl()."\">".$object->name."</a></td>\n";
				echo "<tr>\n";
			}
		?>
</body>
</html>

