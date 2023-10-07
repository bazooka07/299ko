<?php defined('ROOT') OR exit('No direct script access allowed'); ?>
                </div>
                <?php show::displayPublicSidebar(); ?>
            </div>
        </main>
        <div id="footer">
            <div id="footer_content">
                <?php $core->callHook('footer'); ?>
                <p>
                    <a target='_blank' href='https://github.com/299ko/'>Just using 299ko</a> - Thème <?php show::theme(); ?> - <a rel="nofollow" href="<?php echo util::urlBuild('', true); ?>">Administration</a>
                </p>
                <?php $core->callHook('endFooter'); ?>
            </div>
        </div>
    </div>
<?php $core->callHook('endFrontBody'); ?>
</body>
</html>