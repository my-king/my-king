<?php

/**
 * Identifica o navegar sendo utilizado e sua version
 * @author igor
 */
class TBrowserHelper {
    
    public static function getBrowser(){
        
        $var = $_SERVER['HTTP_USER_AGENT'];
        
        $info['browser'] = "OTHER";
        $info['version'] = null;
        
        // valid brosers array
        $browser = array ("MSIE", "OPERA", "FIREFOX", "CHROME","MOZILLA",
                          "NETSCAPE", "SAFARI", "LYNX", "KONQUEROR");
 
        // bots = ignore
        $bots = array('GOOGLEBOT', 'MSNBOT', 'SLURP');
 
        foreach ($bots as $bot)
        {
            // if bot, returns OTHER
            if (strpos(strtoupper($var), $bot) !== FALSE)
            {
                return (object) $info;
            }
        }
 
        // loop the valid browsers
        foreach ($browser as $parent)
        {
            $s = strpos(strtoupper($var), $parent);
            $f = $s + strlen($parent);
            $version = substr($var, $f, 5);
            $version = preg_replace('/[^0-9,.]/','',$version);
            if (strpos(strtoupper($var), $parent) !== FALSE)
            {
                $info['browser'] = $parent;
                $info['version'] = $version;
                return (object) $info;
            }
        }
        
        return (object) $info;
    }
    
}

?>
