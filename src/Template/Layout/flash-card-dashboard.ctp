<!--start of the header -->
<?= $this->element('header-dashboard') ?>
<!--end of the header-->

<div class="row custom-row">
	<!--start of left sidebar dashboard -->
	<?= $this->element('leftsidebar'); ?>
	<!--end of left sidebar dashboard -->
	<div class="col-md-9" id="dashboard-main-content-area">
		<?= $this->fetch('content'); ?>
	</div>
</div>
    <!--start of Footer-->
    <?= $this->element('footer-dashboard-sound'); ?>
<!--end of Footer-->