<?php   
    if(isset($_GET['download_installer'])) {
        header('Content-Type: "application/octet-stream"');
	header('Content-Disposition: attachment; filename="install.php"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header("Content-Transfer-Encoding: binary");
	header('Pragma: public');               
        ob_clean();
	flush();        
        echo "<?php
                     if (file_exists('".(string)$_GET['download_installer']."')) {
                    "."$"."zip = new ZipArchive;
                    if ($"."zip->open('".(string)$_GET['download_installer']."') === TRUE) {
                        $"."zip->extractTo(str_replace('\\\\','/',getcwd()));
                        $"."zip->close();";                       
        echo "header('Location:http://'.$"."_SERVER['HTTP_HOST'].dirname($"."_SERVER['REQUEST_URI']).'/wp_installer.php');\n";        
                    echo "} else {
                        echo 'failed';
                    }
                    }else{
                     echo 'Upload Correct backup File';
                    }
                    ?>";
        exit();
    }
    else {
        die('The provided file path is not valid.');
    }