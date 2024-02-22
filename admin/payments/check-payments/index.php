<?php
require '../../../require/modules.php';
require "../../../require/admin/sessionvariables.php";
require '../../../include/admin/common.php';

$PageTitle = "Cheque Payments";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo $PageTitle; ?> | <?php echo company_name; ?></title>
  <?php include '../../../include/header_files.php'; ?>
</head>

<body>
  <div id="container" class="navbar-fixed mainnav-fixed mainnav-lg">
    <?php include '../../header.php'; ?>

    <!-- main content area -->
    <div class="boxed">
      <!--CONTENT CONTAINER-->
      <!--===================================================-->
      <div id="content-container">
        <div id="page-content">
          <div class="row">
            <div class="col-md-12 col-lg-12 col-12">
              <div class="panel square">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="m-t-3"><i class="fa fa-text-width app-text"></i> All Cheque Payments</h3>
                    </div>
                    <div class="col-md-12">
                      <div class="flex-s-b">
                        <?php include '../common.php'; ?>
                        <div class="btn-group btn-group-sm w-100">
                          <form action="" method="GET" class="flex-s-b form-search-filter w-100">
                            <span class="w-100 p-1 text-right"><b>Search Payments</b></span>
                            <select name="search_type" class="form-control">
                              <option value="bookings.bookingid">Booking ID</option>
                              <option value="bookings.customer_id">Customer ID</option>
                              <option value="bookings.partner_id">Agent ID</option>
                              <option value="check_payments.checkissuedto">Issuer Name</option>
                              <option value="check_payments.checknumber">Cheque Name</option>
                              <option value="check_payments.bankName">Bank Name</option>
                              <option value="check_payments.ifsc">IFSC Code</option>
                              <option value="check_payments.created_at">Receive Date</option>
                              <option value="check_payments.checkstatus">Cheque Status</option>
                              <option value="check_payments.checkamount">Amount</option>
                              <option value="check_payments.clearedat">Clear date</option>
                              <option value="check_payments.bounceat">Bounce Date</option>
                              <option value="check_payments.inbankat">Bank Submit Date</option>
                              <option value="check_payments.issuedat">Issue Date</option>
                            </select>
                            <input type="text" name="search_value" class="form-control" placeholder="Enter Search Value">
                            <button type="submit" class="btn btn-sm btn-default m-l-5" name="search">Search</button>
                          </form>
                        </div>

                      </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-12 col-sm-12 p-l-0 p-r-0 p-t-10">
                      <?php if (isset($_GET['search'])) { ?>
                        <center>
                          <p class="text-center app-bg shadow-lg br10 p-1 w-50 flex-s-b m-b-20">
                            <span><span>Search Results :</span> <?php echo $_GET['search_value']; ?></span>
                            <a href="index.php" class="text-danger"><span class="text-white"><i class="fa fa-times"></i> Clear</span></a>
                          </p>
                        </center>
                      <?php } ?>
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>RefId</th>
                            <th>BookingID</th>
                            <th>Customer</th>
                            <th>CheckNumber</th>
                            <th>BankName</th>
                            <th>IFSC</th>
                            <th>CheckAmount</th>
                            <th>CreatedAt</th>
                            <th>checkstatus</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php

                          if (isset($_GET['search'])) {
                            $search_type = $_GET['search_type'];
                            $search_value = $_GET['search_value'];
                            $CheckCashPayments = CHECK("SELECT * FROM bookings, payments, check_payments where $search_type like '%$search_value%' and payments.payment_id=check_payments.payment_id and bookings.bookingid=payments.bookingid ORDER BY check_payments DESC");
                          } else {
                            $CheckCashPayments = CHECK("SELECT * FROM bookings, payments, check_payments where payments.payment_id=check_payments.payment_id and bookings.bookingid=payments.bookingid ORDER BY check_payments DESC");
                          }

                          if ($CheckCashPayments != 0) {

                            if (isset($_GET['search'])) {
                              $search_type = $_GET['search_type'];
                              $search_value = $_GET['search_value'];
                              $Sql2 = SELECT("SELECT *, check_payments.created_at AS 'checkreceivedat' FROM bookings, payments, check_payments where $search_type like '%$search_value%' and payments.payment_id=check_payments.payment_id and bookings.bookingid=payments.bookingid ORDER BY check_payments DESC");
                            } else {
                              $Sql2 = SELECT("SELECT *, check_payments.created_at AS 'checkreceivedat' FROM bookings, payments, check_payments where payments.payment_id=check_payments.payment_id and bookings.bookingid=payments.bookingid ORDER BY check_payments DESC");
                            }
                            while ($fetch2 = mysqli_fetch_array($Sql2)) {
                              $customer_id = $fetch2['customer_id'];
                              $SelectCustomer = SELECT("SELECT * FROM users where id='$customer_id'");
                              $FetchCustomers = mysqli_fetch_array($SelectCustomer);
                              $CustomerName = $FetchCustomers['name']; ?>
                              <tr>
                                <td><?php echo $fetch2['check_payments']; ?></td>
                                <td>
                                  <a href="<?php echo DOMAIN; ?>/admin/booking/details/?id=<?php echo $fetch2['bookingid']; ?>" class="text-primary text-decoration-underline">B<?php echo $fetch2['bookingid']; ?><?php echo date("/m/Y", strtotime($fetch2['created_at'])); ?></a>
                                </td>
                                <td><a href="<?php echo DOMAIN; ?>/admin/customer/details/?id=<?php echo $customer_id; ?>"><i class="fa fa-user text-info"></i> <?php echo $CustomerName; ?></a></td>
                                <td><?php echo $fetch2['checknumber']; ?></td>
                                <td><?php echo $fetch2['bankName']; ?></td>
                                <td><?php echo $fetch2['ifsc']; ?></td>
                                <td class="text-success">Rs.<?php echo $fetch2['checkamount']; ?></td>
                                <td><?php echo date("d M, Y", strtotime($fetch2['checkreceivedat'])); ?></td>
                                <td><?php echo $fetch2['checkstatus']; ?></td>
                                <td>
                                  <div class="btn-group">
                                    <a href="<?php echo DOMAIN; ?>/admin/booking/emi_receipt.php?id=<?php echo $fetch2['bookingid']; ?>&payment_id=<?php echo $fetch2['payment_id']; ?>" target="blank" class="btn btn-sm btn-primary"><i class="fa fa-print"></i></a>
                                    <a href="update.php?id=<?php echo $fetch2['check_payments']; ?>" class="btn btn-sm btn-default"><i class="fa fa-edit"></i></a>
                                    <?php if (isset($_GET['delete'])) {
                                      if ($_GET['delete'] == "true") {
                                        CONFIRM_DELETE_POPUP(
                                          "remove_payment",
                                          [
                                            "delete_payment_records" => true,
                                            "control_id" => $fetch2['payment_id'],
                                            "payment_mode" => $fetch2['payment_mode']
                                          ],
                                          "paymentcontroller",
                                          "<i class='fa fa-trash'></i>",
                                          "btn btn-sm btn-danger"
                                        );
                                      }
                                    } ?>
                                  </div>
                                </td>
                              </tr>
                          <?php }
                          }
                          ?>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>



    <?php include '../payment-popup.php'; ?>


    <!-- end -->
    <?php include '../../sidebar.php'; ?>
    <?php include '../../footer.php'; ?>
  </div>

  <?php include '../../../include/footer_files.php'; ?>

  <script>
    function PaymentMode(data) {
      if (data == "cash") {
        document.getElementById("cash").style.display = "block";
        document.getElementById("check").style.display = "none";
        document.getElementById("banking").style.display = "none";
      } else if (data == "check") {
        document.getElementById("cash").style.display = "none";
        document.getElementById("check").style.display = "block";
        document.getElementById("banking").style.display = "none";
      } else if (data == "banking") {
        document.getElementById("cash").style.display = "none";
        document.getElementById("check").style.display = "none";
        document.getElementById("banking").style.display = "block";
      } else {
        document.getElementById("cash").style.display = "block";
        document.getElementById("check").style.display = "none";
        document.getElementById("banking").style.display = "none";
      }
    }
  </script>

  <script>
    function getpaidamount() {
      document.getElementById("cashamount").value = document.getElementById("paidamount").value;
      document.getElementById("netpaidamount").innerHTML = document.getElementById("paidamount").value;
      document.getElementById("net_payable").value = document.getElementById("paidamount").value;
    }
  </script>

  <script>
    function chargesCalcu() {
      var chargevalue = document.getElementById("chargevalue").value;
      var chargeshow = document.getElementById("chargeshow");
      var net_payable = document.getElementById("net_payable").value;
      var unit_cost = document.getElementById("paidamount").value;
      var chargename = document.getElementById("chargename").value;
      var discountvalue = document.getElementById("discountvalue").value;
      var discountshow = document.getElementById("discountshow");
      var discountname = document.getElementById("discountname").value;

      if (chargevalue > 0 || discountvalue > 0) {
        chargeshow.style.display = "block";

        if (discountvalue > 0) {
          discountshow.style.display = "block";
          discountamount = Math.round(unit_cost / 100 * discountvalue);
          discountableamount = +unit_cost - +discountamount;
          discountshow.innerHTML = discountname + " (" + discountvalue + "%) : <b> - Rs." + discountamount + "</b>";
          discountname.value = discountname;
          chargeableamount = Math.round(unit_cost / 100 * chargevalue);
          new_net_payable = (+unit_cost + +chargeableamount) - +discountamount;

          document.getElementById("net_payable").value = new_net_payable;
          chargeshow.innerHTML = chargename + " (" + chargevalue + "%): <b> + Rs." + chargeableamount + "</b>";

          document.getElementById("netpaidamount").innerHTML = new_net_payable;
          document.getElementById("paidamount").innerHTML = unit_cost;
          chargename.value = chargename;

        } else {
          discountshow.style.display = "none";
          discountableamount = 0;
          chargename.value = "";
          discountname.value = "";
          chargeableamount = Math.round(unit_cost / 100 * chargevalue);
          new_net_payable = +unit_cost + +chargeableamount;

          document.getElementById("net_payable").value = new_net_payable;
          chargeshow.innerHTML = chargename + " (" + chargevalue + "%): <b> + Rs." + chargeableamount + "</b>";

          document.getElementById("netpaidamount").innerHTML = new_net_payable;
          document.getElementById("paidamount").innerHTML = unit_cost;
          chargename.value = chargename;

        }

      } else {
        chargeshow.style.display = "none";
        discountshow.style.display = "none";

        document.getElementById("net_payable").value = unit_cost;
        document.getElementById("netpaidamount").innerHTML = unit_cost;
        document.getElementById("paidamount").innerHTML = unit_cost;
        chargename.value = "";
        discountname.value = "";
      }

      if (discountvalue > 0) {
        discountshow.style.display = "block";
      } else if (discountvalue == 0) {
        discountshow.style.display = "none";
      } else {
        discountshow.style.display = "none";
      }

      if (chargevalue > 0) {
        chargeshow.style.display = "block";
      } else if (chargevalue == 0) {
        chargeshow.style.display = "none";
      } else {
        chargeshow.style.display = "none";
      }
    }
  </script>

</body>

</html>