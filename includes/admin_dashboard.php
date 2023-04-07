<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <?php
                $sql_donations = $conn->query("SELECT COUNT(donation_id)'total' FROM donations");
                $row_donations = $sql_donations->fetch_assoc();
                $total_donations = $row_donations['total'];
                ?>
                <h3><?= $total_donations; ?></h3>

                <p>Donations</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-wheelchair-alt"></i>
            </div>
            <a href="add-donation" class="small-box-footer">Add New <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <?php
                $sql_users = $conn->query("SELECT COUNT(user_id)'total' FROM users");
                $row_users = $sql_users->fetch_assoc();
                $total_users = $row_users['total'];
                ?>
                <h3><?= $total_users; ?></h3>

                <p>Users</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-users"></i>
            </div>
            <a href="users" class="small-box-footer">Add New <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <?php
                $sql_services = $conn->query("SELECT COUNT(service_id)'total' FROM services");
                $row_services = $sql_services->fetch_assoc();
                $total_services = $row_services['total'];
                ?>
                <h3><?= $total_services; ?></h3>

                <p>Services</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-podcast"></i>
            </div>
            <a href="add-services" class="small-box-footer">Add New <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <?php
                $sql_bank_accounts = $conn->query("SELECT COUNT(bank_acc_id)'total' FROM bank_accounts");
                $row_bank_accounts = $sql_bank_accounts->fetch_assoc();
                $total_bank_accounts = $row_bank_accounts['total'];
                ?>
                <h3><?= $total_bank_accounts; ?></h3>

                <p>Cash Accounts</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-money"></i>
            </div>
            <a href="add-accounts" class="small-box-footer">Add New <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <?php
                $sql_income_source = $conn->query("SELECT COUNT(income_source_id)'total' FROM income_source");
                $row_income_source = $sql_income_source->fetch_assoc();
                $total_income_source = $row_income_source['total'];
                ?>
                <h3><?= $total_income_source; ?></h3>

                <p>Donation Type</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-wheelchair"></i>
            </div>
            <a href="add-donation-type" class="small-box-footer">Add New <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <?php
                $sql_project_expense = $conn->query("SELECT COUNT(project_expense_id)'total' FROM project_expense");
                $row_project_expense = $sql_project_expense->fetch_assoc();
                $total_project_expense = $row_project_expense['total'];
                ?>
                <h3><?= $total_project_expense; ?></h3>

                <p>Project Expenses</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-line-chart"></i>
            </div>
            <a href="add-helping-category" class="small-box-footer">Add New <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <?php
                $sql_expense = $conn->query("SELECT COUNT(exp_id)'total' FROM expense");
                $row_expense = $sql_expense->fetch_assoc();
                $total_expense = $row_expense['total'];
                ?>
                <h3><?= $total_expense; ?></h3>

                <p>Expenses</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-rocket"></i>
            </div>
            <a href="add-expense" class="small-box-footer">Add New <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <?php
                $sql_expenses = $conn->query("SELECT COUNT(expense_id)'total' FROM expenses");
                $row_expenses = $sql_expenses->fetch_assoc();
                $total_expenses = $row_expenses['total'];
                ?>
                <h3><?= $total_expenses; ?></h3>

                <p>Expense Type</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-sitemap"></i>
            </div>
            <a href="add-expense-type" class="small-box-footer">Add New <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Services</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="services" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Service Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $services_sql = $conn->query("SELECT * FROM services LIMIT 5");
                                $i = 1;
                                while ($service = mysqli_fetch_assoc($services_sql)) :
                            ?>
                                <tr>
                                    <td><?= $service['service_name'];?></td>
                                </tr>
                            <?php endwhile;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Donation Types</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Donation Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $donation_types_sql = $conn->query("SELECT * FROM income_source LIMIT 5");
                                $i = 1;
                                while ($donation_types = mysqli_fetch_assoc($donation_types_sql)) :
                            ?>
                                <tr>
                                    <td><?= $donation_types['income_source_title'];?></td>
                                </tr>
                            <?php endwhile;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Expense Types</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Expense Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $expense_types_sql = $conn->query("SELECT * FROM expenses LIMIT 5");
                                $i = 1;
                                while ($expense_types = mysqli_fetch_assoc($expense_types_sql)) :
                            ?>
                                <tr>
                                    <td><?= $expense_types['expense_title'];?></td>
                                </tr>
                            <?php endwhile;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>