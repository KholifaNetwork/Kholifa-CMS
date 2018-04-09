<?php // @Layout name: Main ?>
<?php echo knDoctypeDeclaration(); ?>
<html<?php echo knHtmlAttributes(); ?>>
<head>
    <?php
        knAddCss('content/Internal/Core/assets/knContent/knContent.css'); // include default CSS for widgets
        knAddCss('assets/theme.css');
        echo knHead();
    ?>
</head>
<body>
    <div class="topmenu">
        <?php echo knSlot('menu', 'menu1'); ?>
    </div>
    <div class="content">
        <?php echo knBlock('main')->render(); ?>
    </div>
    <?php
        knAddJs('assets/theme.js');
        echo knJs();
    ?>
</body>
</html>
