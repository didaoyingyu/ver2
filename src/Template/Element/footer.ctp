<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?= $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>
    <?php if(isset($curPage) && $curPage=='register') : ?>
    <?= $this->Html->script('custom.js'); ?>
    <?php endif; ?>
    <?php if(isset($curPage) && $curPage=='dashboard') : ?>
    <?= $this->Html->script('dashboard.js'); ?>
    <?php endif; ?>



    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <?= $this->Html->script('ie10-viewport-bug-workaround.js') ?>
  </body>
</html>