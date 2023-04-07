<?php ob_start(); ?>
<?php $page = 'donation_type'; ?>
<?php $title = "Donation Type"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if($isAdmin != 1){
    if($donation_type_access == false){
        header('Location: index.php');
    } 
}?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Donation Types</h3>
                            <div class="card-tools">
                                <button type="button" name="add" id="addIncome" class="btn btn-dark btn-sm pull-right">New Donation Type</button>
                            </div>
                        </div>
                        <div class="card-body" id="view_incomes">
                            <div class="table-responsive">
                                <table id="incomeList" class="table table-bordered table-striped">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Donation Type</th>
                                            <?php if($isAdmin == 1){?>
                                            <th><i class="fa fa-cogs"></i></th>
                                            <?php }?>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <form method="post" id="incomeForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                            <label for="income_source_title">Donation Type</label>
                                <input type="text" name="income_source_title" id="income_source_title" class="form-control" placeholder="Enter Donation Type" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="hidden" name="editId" id="editId" value="" />
                            <input type="hidden" name="action" id="action" value="" />
                        </div>
                    </div>
                    <div class="modal-footer">
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" name="save" id="save" class="btn bg-info"> Save </button>
    				</div>
                </form>
            </div>
        </div>
    </div>
</div>	
<?php include "includes/footer.php"; ?>
<style>
    .focused {
        border-color: red !important;
    }
    .text{
        color:red !important;
    }
</style>
<script>
    $(document).ready(function(){	
	var incomeRecords = $('#incomeList').DataTable({
		"responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "bProcessing": true,
		"order":[],
		"ajax":{
			url:"income/process.php",
			type:"POST",
			data:{action:'listIncome'},
			dataType:"json"
		},
		"columnDefs": [{
            "targets": [-1], //last column
            "orderable": false, //set not orderable
        }, ],
		"pageLength": 10
	});		
	$('#addIncome').click(function(){
		$('#myModal').modal('show');
		$('#incomeForm')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> Add New Donation Type");
		$('#action').val('addIncome');
		$('#save').val('Add');
	});		
	$("#incomeList").on('click', '.update', function(){
		var editId = $(this).attr("id");
		var action = 'getIncome';
		$.ajax({
			url:'income/process.php',
			method:"POST",
			data:{editId:editId, action:action},
			dataType:"json",
			success:function(data){
				$('#myModal').modal('show');
				$('#editId').val(data.income_source_id);
                $('#income_source_title').val(data.income_source_title);	
				$('.modal-title').html("<i class='fa fa-plus'></i> Edit Donation Type");
				$('#action').val('updateIncome');
                $('#save').val('Save');
			}
		})
	});
	$("#myModal").on('submit','#incomeForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"income/process.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#incomeForm')[0].reset();
				$('#myModal').modal('hide');				
				$('#save').attr('disabled', false);
				incomeRecords.ajax.reload();
                $.notify("Donation Type Added Successfully", "success");
			}
		})
	});		
	$("#incomeList").on('click', '.delete', function(){
		var editId = $(this).attr("id");		
		var action = "deleteIncome";
		if(confirm("Are you sure you want to delete this income?")) {
			$.ajax({
				url:"income/process.php",
				method:"POST",
				data:{editId:editId, action:action},
				success:function(data) {					
					incomeRecords.ajax.reload();
                    $.notify("Donation Type Deleted Successfully", "danger");
				}
			})
		} else {
			return false;
		}
    });	
});
</script>
<?php ob_end_flush();?>