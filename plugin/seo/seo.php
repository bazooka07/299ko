<?php
defined('ROOT') OR exit('No direct script access allowed');

## Fonction d'installation

function seoInstall(){
}

## Hooks

function seoEndFrontHead(){
    $plugin = pluginsManager::getInstance()->getPlugin('seo');
    $temp = "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '".$plugin->getConfigVal('trackingId')."', 'auto');
  ga('send', 'pageview');

</script>";
    $temp.= '<meta name="google-site-verification" content="'.$plugin->getConfigVal('wt').'" />';
    echo $temp;
}

function seoEndFrontBody(){
        echo '<div id="seo_social_float"><ul>';
        echo seoGetSocialIcons('<li>', '</li>');
        echo '</ul></div>';
}

function seoMainNavigation() {
    echo seoGetSocialIcons('<li class="seo_element">', '</li>' );
}

function seoFooter() {
    echo '<div id="seo_social"><ul>';
    echo seoGetSocialIcons('<li>', '</li>');
    echo '</ul></div>';
}

function seoGetSocialIcons($before = '', $after = '') {
    $social = seoGetSocialVars();
    $plugin = pluginsManager::getInstance()->getPlugin('seo');
    $str = "";

    foreach ($social as $k => $v) {
        $tConfig = $plugin->getConfigVal($v);
        if ($tConfig !== '') {
            $str .=  $before . '<a target="_blank" title="Suivez-nous sur ' . $k . '" href="' . $tConfig . '"><i class="fa-brands fa-' . $v . '"></i></a>' . $after;
        }
    }
    return $str;
}

function seoGetSocialVars() {
    return [
        'Facebook' => 'facebook',
        'Twitter' => 'twitter',
        'YouTube' => 'youtube',
        'Instagram' => 'instagram',
        'Pinterest' => 'pinterest',
        'Linkedin' => 'linkedin',
        'Viadeo' => 'viadeo',
        'GitHub' => 'github'
    ];
}
