<?php echo knDoctypeDeclaration(); ?>
<html<?php echo knHtmlAttributes(); ?>>
<head>
    <?php knAddCss('assets/theme.css'); ?>
    <?php echo knHead(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="<?php echo knContent()->getCurrentLanguage()->getTextDirection() ?>">
<div class="wrapper clearfix">
    <header class="clearfix col_12">
        <?php echo knSlot('logo'); ?>
        <div class="right">
            <span class="currentPage"><?php echo esc(knContent()->getCurrentPage() ? knContent()->getCurrentPage()->getTitle() : ''); ?></span>
            <a href="#" class="topmenuToggle">&nbsp;</a>
            <div class="topmenu">
                <?php echo knSlot('menu', 'menu1'); ?>
                <?php if (count(knContent()->getLanguages()) > 1) { ?>
                    <div class="languages">
                        <?php echo knSlot('languages'); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </header>
