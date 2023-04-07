<?php ob_start(); ?>
<?php $page = 'backup'; ?>
<?php $title = "Backup"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if($isAdmin != 1){
    header('Location: index.php');
}?>
<?php include('backup/process.php'); ?>
<!-- Main Sidebar Container -->
<div class="content-wrapper">
    <section class="content">
		<div class="card">
		    <div class="card-body">
		      	<h3>Database Backup</h3><br>   
                  <form action="" method="post">
                    <div class="col-md-12">
                        <button type="submit" name="create_backup" class="btn btn-success"><i class="fa fa-download"></i> &nbsp; Download &amp; Create Backup</button>
                    </div>
                </form>
		    </div>
		</div>
	</section>
</div>

<?php include "includes/footer.php"; ?>
<?php ob_end_flush();?>
