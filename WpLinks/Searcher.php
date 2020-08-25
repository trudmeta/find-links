<?php

namespace WpLinks;

use WpLinks\SearchInterface;
use DOMDocument;

class Searcher implements SearchInterface
{
    /**
     * Поиск ссылок в контенте
     */
    public function findLinks($content = ''): string
    {
        $dom = new DOMDocument;
        $dom->loadHTML($content, LIBXML_HTML_NODEFDTD);

        $nofollow = (bool)get_option('wplink_nofollow', false);
        $target = (bool)get_option('wplink_target', false);

        foreach($dom->getElementsByTagName('a') as $node) {
            if($nofollow) {
                $node->setAttribute('rel', 'nofollow');
            } else {
                $node->removeAttribute('rel');
            }
            if($target) {
                $node->setAttribute('target', '_blank');
            } else {
                $node->removeAttribute('target');
            }
        }
        return substr(utf8_decode($dom->saveHTML($dom->documentElement)), 12, -15);
    }

    /**
     * Поиск ссылок в метаполях
     */
    public function findLinksInMeta(int $id): void
    {
        $postMeta = get_post_meta($id);
        $nofollow = (bool)get_option('wplink_nofollow', false);
        $target = (bool)get_option('wplink_target', false);

        foreach($postMeta as $key => $meta){
            $meta = $meta[0];
            preg_match('/<a\\s.*?href=".+?"\\s?.*?(rel="nofollow")?\\s?.*?(target="_blank")?\\s?.*?>.+?<\\/a>/im', $meta, $link);
            if(!empty($link)){
                if($nofollow){
                    if(empty($link[1])){
                        $meta = preg_replace('/(<a\\s.*?\\s?)>/im', '$1 rel="nofollow">', $meta);
                    }
                }else{
                    if(!empty($link[1])){
                        $meta = preg_replace('/(<a\\s.*?)(rel="nofollow")\\s?.*?>/im', '$1>', $meta);
                    }
                }
                if($target){
                    if(empty($link[2])){
                        $meta = preg_replace('/(<a\\s.*?)\\s?>/im', '$1 target="_blank">', $meta);
                    }
                }else{
                    if(!empty($link[2])){
                        $meta = preg_replace('/(<a\\s.*?)(target="_blank")\\s?.*?>/im', '$1>', $meta);
                    }
                }
                update_post_meta($id, $key, $meta);
            }
        }
    }

}
