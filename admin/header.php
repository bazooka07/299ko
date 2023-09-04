<?php
/**
 * @copyright (C) 2022, 299Ko, based on code (2010-2021) 99ko https://github.com/99kocms/
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @author Jonathan Coulet <j.coulet@gmail.com>
 * @author Maxence Cauderlier <mx.koder@gmail.com>
 * @author Frédéric Kaplon <frederic.kaplon@me.com>
 * @author Florent Fortat <florent.fortat@maxgun.fr>
 * 
 * @package 299Ko https://github.com/299Ko/299ko
 */
defined('ROOT') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="<?php echo lang::getLocale(); ?>">
    <head>
        <?php $core->callHook('adminHead'); ?>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=5">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>299ko - <?php echo lang::get('core-administration'); ?></title>	
        <link rel="icon" href=" data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABb2lDQ1BpY2MAACiRdZE7SwNBFIU/EyXigxRaiCik8FUYCApiqbFIE0Sigq8mu9lNhCQuuxsk2Ao2FgEL0cZX4T/QVrBVEARFELHyB/hqJKx3EiEicZbZ+3FmzmXmDPjiWT3nNEYgl3ftRCwaWlhcCgVeaKGXAIMEk7pjTc7MxPl3fN7RoOptWPX6f1/d0ZoyHB0amoXHdMt2hSeE4+uupXhbuFPPJFPCh8LDthxQ+ErpWpWfFaer/K7YnktMgU/1DKV/sfaL9YydEx4S7stlC/rPedRN2oz8/KzUbpk9OCSIESWERoFVsriEpeYls/q+SMU3zZp4dPlbFLHFkSYj3mFRC9LVkGqKbsiXpahy/5unY46OVLu3RaHpyfPe+iGwA+WS530deV75GPyPcJGv+dckp/EP0Us1re8AgptwdlnTtF0434KuBytpJyuSX6bPNOH1FNoXoeMGWparWf2sc3IPcxvyRNewtw8Dsj+48g3mO2f+n7tX+AAAAAlwSFlzAAAOwwAADsMBx2+oZAAAAi1JREFUOMtjZEADN2/dZX3/4WPX06fPo4BcASBm+s/A8FFMRHgzGztbloWZ0XcGfODb9+8Ca9ZteWFg6vpfQ8/uvyYQ6xo5/V+8bO3nT58+S6KrZ0IXuHr1xmdzM6MeX2/Xb+/efWB48/Y9g6uL/U87G/Oes+cuvUVXz4IuYGpi+Pf///9zpKUk8llZWbj+/v3HIC0l/llOVnqmvJzML4IugALGf0BTYJx//8BMRmwKcRlg8P37d76///4xgMz59u07F1DMiIEQACl+9vxlzqSpc98Zmrn9l1Iw+i+taAQOxO6+6Z8ePHxcBVTDhNOAN2/fhdU39fyXkDf4Lylv+F9W2QSMpRQM/4vJ6v0vrWz+f/fewwycgXjg4LHKFas2fGVmYmIAYg6gzz8BPX6NhZn5HhMjk/uatVv+a2upVwCVzsBqwJmzl/RfPHmeISwidJThPwMfMAReCTAwPbxw99QfOWWTSe/evf959erNFJwuACYUhs8fPl3++OHuVSw+vA/EvJ+/fPmIMxZ4ebkZuXi5lbCFDxsj45ovnz6f4uLklMBpgL6+9g05Rbl0kHp0A+7cOf1YXkk+UFNT9T3OWHj1+k14XWP3P3Fp3blAM6SQpAT4RdXrSsqb/t++cy8LZxiIiYqsBKYDCT4+no4duw74PHv6/Agw3n9LSkqYOzvZSAUFeDWpKCvORNaDNXkCNZlcuHQ1+uKla17//v5j1dZW32lmYrCEkZHxKLpaAM7P7FOQafyUAAAAAElFTkSuQmCC">
        <?php show::linkTags(); ?>
        <link rel="stylesheet" href="styles.css" media="all">
        <?php show::scriptTags(); ?>
        <script type="text/javascript" src="scripts.js"></script>
        <?php $core->callHook('endAdminHead'); ?>	
    </head>
    <body>
        <div id="container">
            <aside id="header">
                <nav id="adminNav">
                    <label id="labelBurger" for="burger"><i class="fa-solid fa-bars"></i></label>
                    <input type="checkbox" id="burger"/>
                    <div class="main_nav" role="navigation" aria-label="<?php echo lang::get('core-nav-admin'); ?>">
                        <ul id="navigation">
                            <li id="nav-change-container"><a role="button" href="#" onClick="changeMainNav()"><i title="<?php echo lang::get('admin-change-sidebar-width'); ?>" id="nav-icon-grow"></i></a></li>
                            <li class="site"><a target="_blank" href="../"><i title="<?php echo lang::get('core-goto-site'); ?>" class="fa-regular fa-eye"></i><span><?php echo lang::get('core-goto-site'); ?></span></a></li>
                            <?php show::adminNavigation(); ?>
                            <li class="site"><a href="<?php echo util::urlBuild('?action=logout&token=' . administrator::getToken(), true); ?>"><i title="<?php echo lang::get('core-disconnection'); ?>" class="fa-solid fa-arrow-right-from-bracket"></i><span><?php echo lang::get('core-disconnection'); ?></span></a></li>
                            <li class="just_using">
                                <a target="_blank" href="https://github.com/299ko/"><i title="<?php echo lang::get('site-just-using', VERSION); ?>" class="fa-solid fa-flask"></i><span><?php echo lang::get('site-just-using', VERSION); ?></span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </aside>
            <div id="alert-msg">
                <?php show::displayMsg(); ?>
            </div>
            <div id="body">
                <div id="content_mask">
                    <div id="content" class="<?php echo $runPlugin->getName(); ?>-admin">
                        <?php if ($runPlugin->getParamTemplate()) { ?>
                            <a title="<?php echo lang::get('core-settings'); ?>" data-fancybox id="param_link" href="#" data-src="#param_panel"><i class="fa-solid fa-screwdriver-wrench"></i></a>
                            <div id="param_panel">
                                <div class="content">
                                    <h2><?php echo lang::get('core-settings'); ?></h2>
                                    <?php include($runPlugin->getParamTemplate()); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($runPlugin->getHelpTemplate()) { ?>
                            <div id="help_panel">
                                <div class="content">
                                    <h2><?php echo lang::get('core-help'); ?></h2>
                                    <?php include($runPlugin->getHelpTemplate()); ?>
                                </div>
                            </div>
                            <a title="<?php echo lang::get('core-help'); ?>" data-fancybox id="help_link" href="#" data-src="#help_panel"><i class="fa-solid fa-circle-question"></i></a>
                        <?php } ?>
                        <div id="page-infos">
                            <h2><?php echo $runPlugin->getInfoVal('name'); ?></h2>
                            <?php $core->callHook('afterAdminTitle'); ?>
                        </div>