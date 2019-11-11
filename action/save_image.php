<?php
if ( isset ( $GLOBALS["HTTP_RAW_POST_DATA"] )) {
    $uniqueStamp = date('U');
    $filename = "../temp/".$uniqueStamp.".jpeg";
    $fp = fopen( $filename,"wb");
    fwrite( $fp, $GLOBALS[ 'HTTP_RAW_POST_DATA' ] );
    fclose( $fp );
 
    echo "filename=".$uniqueStamp;
}
?>

