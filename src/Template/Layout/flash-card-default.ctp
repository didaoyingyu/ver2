<!--start of the header -->
<?= $this->element('header') ?>
<!--end of the header-->
    <div class="container">
      <div class="row">
        <?= $this->fetch('content') ?>  
      </div>
    </div>
    <!--start of Footer-->
    <?= $this->element('footer'); ?>
<!--end of Footer-->