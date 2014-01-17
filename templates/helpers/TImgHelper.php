<?php

/**
 * Cria a tag img
 * @author igor
 */
class TImgHelper {
    public static function img($url, $id = "", $class = "", $alt = "",  $title = "", $height = "", $width = "", $atributes = ""){
        
        $height = ($height != "") ? " height='{$height}'" : "";
        $width = ($width != "") ? " width='{$width}'" : "";
        $id = ($id != "") ? "id='{$id}'" : "";
        $class = ($class != "") ? " class='{$class}'" : "";
        $alt = ($alt != "") ? " alt='{$alt}'" : "";
        $title = ($title != "") ? " title='{$title}'" : "";
        $atributes = ($atributes != "") ? " ".$atributes : "";
        
        $img = "<img src='$url'";
        $img .= $height;
        $img .= $width;
        $img .= $id;
        $img .= $class;
        $img .= $alt;
        $img .= $title;
        $img .= $atributes;
        $img .= " />";
        
        return $img;        
    }
}

?>
