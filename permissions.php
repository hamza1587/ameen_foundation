<?php ob_start(); ?>
<?php
include 'includes/db.php';
    $page = 'user';
    $sub_page = 'permissions';
    $title = "Permissions";
    include "includes/main_sidebar.php";
    if($isAdmin != 1){
        if($roles_access == false){
            header('Location: index.php');
        } 
    }
?>
    <div class="content-wrapper" style="min-height: 600px;padding-top:30px;">
        <!-- Main content -->
        <section class="content">
            <div class="card card-default color-palette-bo">
                <div class="card-header">
                    <div class="d-inline-block">
                        <h3 class="card-title"> <i class="fa fa-edit"></i>
                            &nbsp; Permissions </h3>
                    </div>
                    <div class="d-inline-block float-right">
                        <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Horizontal Form -->
                            <div class="box box-info">
                                <form class="form-horizontal" id="roles_form" method="post">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead class="bg-info">
                                                            <tr>
                                                                <th>#</th>
                                                                <th class="text-center">Modules</th>
                                                                <th class="text-center">Specific Permissions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $i = 1;
                                                            $sql1 = $conn->query("SELECT * FROM modules");
                                                            if (mysqli_num_rows($sql1) > 0) :
                                                                while ($row_permissions = $sql1->fetch_assoc()) {
                                                                $sql = $conn->query("SELECT * FROM permission INNER JOIN modules ON modules.module_id = permission.module_id WHERE permission.module_id =".$row_permissions['module_id']." AND role_id=" .urldecode($_REQUEST['permission']));
                                                                $str = str_replace("_"," ",$row_permissions['module_name']);
                                                            ?>
                                                                    <tr>
                                                                        <td><?= $i++; ?></td>
                                                                        <td class="text-center"><?= ucfirst($str); ?></td>
                                                                        <td class="text-center">
                                                                            <input type="checkbox" class="tgl_checkbox tgl-ios" data-id="<?= $row_permissions['module_id']; ?>" id="<?= $row_permissions['module_name']; ?>" 
                                                                            <?php while ($row_permission = $sql->fetch_assoc()) {
                                                                            if($row_permission['permissions'] == "1"){
                                                                                echo 'checked';
                                                                            }else{
                                                                                echo 'unchecked';
                                                                            }
                                                                            }?>>
                                                                            <label for="<?= $row_permissions['module_name']; ?>"></label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php endif; ?>
                                                        </tbody>
                                                        <input type="hidden" id="role" value="<?= urldecode($_REQUEST['permission']); ?>">
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <!-- /.box -->
                        </div>
                        <!--/.col (right) -->
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include "includes/footer.php"; ?>
    <script type="text/javascript">
        $(document).on("change",".tgl_checkbox",function(){
            $.post('<?= $redirect ?>permissions/change_status.php',
            {
                id : $(this).data('id'),
                role : $("#role").val(),
                status : $(this).is(':checked') == true?1:0
            },
            function(data){
                $.notify("Status Changed Successfully", "success");
            });
        });
    </script>
<?php ob_end_flush();?>
