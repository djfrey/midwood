<?php
require_once('../../config.php');

$action = $_GET['action'];
$type = $_GET['element_type'];
$name = $_GET['name'];
$path = $_GET['path'];

$basepath = dirname($path);
//Only allow file manipulation within the HSR document path
if (!strstr($path, $GLOBALS['doc_path'])) {
	die("error=Select a folder for your upload");
}

switch ($action) {
	case "add":
		switch ($type) {
			case "directory":
				add_directory($path, $name);
			break;
			case "file":
				add_file($path);
			break;
		}		
	break;
	case "rename":
		switch ($type) {
			case "directory":
				rename_directory($path, $name);
			break;
			case "file":
				rename_file($path, $name);
			break;
		}		
	break;
	case "delete":
		switch ($type) {
			case "directory":
				delete_directory($path);
			break;
			case "file":
				delete_file($path);
			break;
		}	
	break;
}

function rename_file($path, $name) {
	$parent = dirname($path);
	if (@rename($path, $parent."/".$name)) {
		$out = "rename_file=success";
	} else {
		$out = "error=Error renaming file";
	}
	print $out;
}

function rename_directory($path, $name) {
	$parent = dirname($path);
	if (@rename($path, $parent."/".$name)) {
		$out = "rename_directory=success";
	} else {
		$out = "error=Error renaming directory";
	}
	print $out;
}
	
function delete_file($filepath) {
	if(@is_file($filepath)) {
		if(@unlink($filepath)) {
			$out = "delete_file=success";
		} else {
			$out = "error=Error deleting file";
		}
	} else {
		$out = "error=No file selected for deletion";
	}
	print $out;
}

function delete_directory($dirname) {
	if (@is_dir($dirname)) {
		$dir_handle = opendir($dirname);
	}
    if (!$dir_handle) { 
		$out = "error=No directory selected for deletion";
		print $out;
		return false; 
	}
	$i = 0;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
   			$i++;
        }
    }
    closedir($dir_handle);
	if ($i > 0) {
		$out = "error=Directory is not empty";
	} else {
		if (@rmdir($dirname)) {
			$out = "delete_directory=success";			
		} else {
			$out = "error=Error deleting directory";	
		}
	}
	print $out;
}

function add_directory($path, $name) {
	if (@mkdir($path.$name)) {
		$out = "add_directory=success";	
	} else {
		$out = "error=Error adding directory";
	}
	print $out;
}

function add_file($path) {
	if ($_FILES["file"]["error"] > 0) {
  		$out = "error=".$_FILES["file"]["error"];
	} else {
		$filename = basename($_FILES["file"]["name"]);
  		if (file_exists($path.$filename)) {
			$out = "error=A file with that name exists in this folder";
		} else {
     		if (move_uploaded_file($_FILES["file"]["tmp_name"], $path.$filename)) {
				$pathinfo = pathinfo($path.$filename);
				$out = sprintf("add_file=success&filename=%s&fileext=%s", $pathinfo['filename'], $pathinfo['extension']);
			} else {
				$out = "error=Error uploading file";
			}
    	}
  	}
	print $out;
}
?>