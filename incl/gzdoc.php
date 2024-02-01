<?
/*
** Title.........: PHP4 HTTP Compression Speeds up the Web
** Version.......: 1.20
** Author........: catoc <catoc@163.net>
** Filename......: gzdoc.php
** Last changed..: 18/10/2000
** Requirments...: PHP4 >= 4.0.1
**                 PHP was configured with --with-zlib[=DIR]
** Notes.........: Dynamic Content Acceleration compresses
**                 the data transmission data on the fly
**                 code by sun jin hu (catoc) <catoc@163.net>
**                 Most newer browsers since 1998/1999 have
**                 been equipped to support the HTTP 1.1
**                 standard known as "content-encoding."
**                 Essentially the browser indicates to the
**                 server that it can accept "content encoding"
**                 and if the server is capable it will then
**                 compress the data and transmit it. The
**                 browser decompresses it and then renders
**                 the page.
**
**                 Modified by John Lim (jlim@natsoft.com.my)
**                  based on ideas by Sandy McArthur,  Jr
** Usage........:
**                 No space before the beginning of the first '<?' tag.
**                 ------------Start of file----------
**                 |<?
**                 | include('gzdoc.php'); 
**                 |?>
**                 |<HTML>
**                 |... the page ...
**                 |</HTML>
**                 |<?
**                 | gzdocout(); 
**                 |?>
**                 -------------End of file-----------
*/
ob_start(); 
ob_implicit_flush(0); 
function CheckCanGzip(){
    global $HTTP_ACCEPT_ENCODING; 
    if (headers_sent() || connection_aborted()){
        return 0; 
    }
    if (strpos($HTTP_ACCEPT_ENCODING,  'x-gzip') !== false) return "x-gzip"; 
    if (strpos($HTTP_ACCEPT_ENCODING, 'gzip') !== false) return "gzip"; 
    return 0; 
}
/* $level = compression level 0-9,  0=none,  9=max */
function GzDocOut($level=3, $debug=0){
    $ENCODING = CheckCanGzip(); 
    if ($ENCODING){
        print "\n<!-- Use compress $ENCODING -->\n"; 
        $Contents = ob_get_contents(); 
        ob_end_clean(); 
        if ($debug){
            $s = "<center><font style='color:#C0C0C0;
                  font-size:9px; font-family:tahoma'>Not compress
                  length: ".strlen($Contents).";  "; 
            $s .= "Compressed length: ".
                   strlen(gzcompress($Contents, $level)).
                   "</font></center>"; 
            $Contents .= $s; 
        }
        header("Content-Encoding: $ENCODING"); 
        print "\x1f\x8b\x08\x00\x00\x00\x00\x00"; 
        $Size = strlen($Contents); 
        $Crc = crc32($Contents); 
        $Contents = gzcompress($Contents, $level); 
        $Contents = substr($Contents,  0,  strlen($Contents) - 4); 
        print $Contents; 
        print pack('V', $Crc); 
        print pack('V', $Size); 
        exit; 
    }else{
        ob_end_flush(); 
        exit; 
    }
}
?>