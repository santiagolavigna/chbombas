<?php
class Utils{

    static $stopLog=false;

    static function log($txt){
        if(Utils::$stopLog) return null;
        $txt="\n".Utils::getFecha()." ".Utils::getCaller(
        debug_backtrace(false))."\n".$txt;
        $txt=$txt."----------------------------------------\n";
        $myfile = fopen("logs/LOG.txt", "a") or die("ERROR UTILS!");
        fwrite($myfile, print_r($txt,true));
        fclose($myfile);
    }
    
    static function log_facturacion($txt){
        if(Utils::$stopLog) return null;
        $txt="\n".Utils::getFecha()." ".Utils::getCaller(
        debug_backtrace(false))."\n".$txt;
        $txt=$txt."----------------------------------------\n";
        $myfile = fopen("logs/LOG_FACTURACION.txt", "a") or die("ERROR UTILS!");
        fwrite($myfile, print_r($txt,true));
        fclose($myfile);
    }
    
      static function log_facturacion_err($txt){
        if(Utils::$stopLog) return null;
        $txt="\n".Utils::getFecha()." ".Utils::getCaller(
        debug_backtrace(false))."\n".$txt;
        $txt=$txt."----------------------------------------\n";
        $myfile = fopen("logs/LOG_FACTURACION_ERRORES.txt", "a") or die("ERROR UTILS!");
        fwrite($myfile, print_r($txt,true));
        fclose($myfile);
    }
    
    static function log_deleteds($txt){
        if(Utils::$stopLog) return null;
        $txt="\n".Utils::getFecha()." ".Utils::getCaller(
        debug_backtrace(false))."\n".$txt;
        $txt=$txt."----------------------------------------\n";
        $myfile = fopen("logs/LOG_DELETEDS.txt", "a") or die("ERROR UTILS!");
        fwrite($myfile, print_r($txt,true));
        fclose($myfile);
    }

    static function getFecha(){
        $h = getdate();
        $f=$h['year']."-".$h['mon']."-".$h['mday'];
        $f=$f." ".$h['hours'].":".$h['minutes'];
        return $f;
    }

    static function getCaller($debug){
        $f = $debug[1]['function'];
        if(isset($debug[1]['class'])) $c = $debug[1]['class']; else $c="";
        if($c!="") return "< ".$f." in class ".$c." >";
        return "< ".$f." in file ".$debug[1]['file']." >";
    }
}
?>