<footer class="clearfix">
    <div class="col_12">
        <?php echo knSlot('text', array('id' => 'themeName', 'tag' => 'div', 'default' => __('Theme "Kholifa Default"', 'Kholifa Default', false), 'class' => 'left')); ?>
        <div class="right">
            <?php echo sprintf(__('Drag & Powered by %s', 'Kholifa Default'), '<a href="http://kholifa.com">Kholifa Network</a>'); ?>
        </div>
    </div>
</footer>
</div>
<?php echo knAddJs('assets/site.js'); ?>
<?php echo knJs(); ?>

</body>
</html>
