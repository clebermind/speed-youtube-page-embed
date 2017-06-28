<?php
/*
 * Plugin Name: Speed YouTube Page Embed
 * Plugin URI: http://www.comunicas.com.br/
 * Description: Embed Youtube Video using shortcode [youtube_iframe]. It shows a youtube thumb of the video(or custom thumb setted) and load the youtube video just when clicked. Perfect to improve the pagespeed!
 * Author: Cleber Mendes
 * Author URI: ttp://www.comunicas.com.br/
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
    die( 'No script kiddies please!' );
}

$assetsPath = plugins_url( 'assets', __FILE__ );

wp_enqueue_style('youtube-embed-page-css', $assetsPath . '/css/style-min.css');
wp_enqueue_script('youtube-embed-page-script', $assetsPath . '/js/main-min.js', array('jquery'));

add_shortcode('youtube_iframe','youtube_iframe_callback');

function youtube_iframe_callback($attr)
{
    
    if( !isset($attr['url']) ) {
        return false;
    }
        
    if (strpos($attr['url'], 'www.youtube.com/watch') !== false) {
        $code = explode('?v=', $attr['url'])[1];
        $attr['url'] = 'https://www.youtube.com/embed/' . $code;
    } else {
         $code = explode('/embed/', $attr['url'])[1];
    }

    if( !isset($attr['thumb']) ) {
        $attr['thumb'] = "https://img.youtube.com/vi/{$code}/0.jpg";
    }
    
    $size = array(
                  'w'=>isset($attr['width']) ? $attr['width'] : 560,
                  'h'=>isset($attr['height']) ? $attr['height'] : 320
                 );
    
    $id = md5($code . rand());
    $content = "<div class='page_youtube_item' id='page_youtube_item{$id}' data-v='{$code}' data-w='{$size['w']}' data-h='{$size['h']}'>
                    <div class='play_image'></div><img class='image_thumb' src='{$attr['thumb']}' width='{$size['w']}' height='{$size['h']}' />
                </div>
                <style> #page_youtube_item{$id} { height: {$size['h']}px; width: {$size['w']}px;} </style>";
                
    return $content;

}