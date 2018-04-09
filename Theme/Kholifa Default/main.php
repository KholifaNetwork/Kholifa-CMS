<?php // @Layout name: Main ?>
<?php echo knView('_header.php')->render(); ?>
    <div class="sidenav col_12 col_md_12 col_lg_3 left">
        <nav<?php if (knGetThemeOption('collapseSidebarMenu') == 'yes') { echo ' class="collapse"'; }?>>
            <?php
                // generate 2 - 7 levels submenu
                // please note that it is possible to generate second level only if first level item is in breadcrumb
                // $pages = \including\Menu\Helper::getMenuItems('menu1', 2, 7);
                // echo knSlot('menu', $pages);

                 //submenu of currently active menu item
                 //$pages = \including\Menu\Helper::getChildItems();
                 //echo knSlot('menu', $pages);

                echo knSlot('menu', 'menu2');
            ?>
        </nav>
    </div>
    <div class="main col_12 col_md_12 col_lg_8 right">
        <?php echo knSlot('breadcrumb'); ?>
        <?php echo knBlock('main')->render(); ?>
    </div>
    <div class="side col_12 col_md_12 col_lg_3 left">
        <aside>
            <?php echo knBlock('side1')->asStatic()->render(); ?>
        </aside>
    </div>
    <div class="clear"></div>
<?php echo knView('_footer.php')->render(); ?>
