<?php

//add controller helper files
require 'helper.php';

//add aditional requirements
require '../require/admin/sessionvariables.php';

//start request processing

if (isset($_POST['CreateDevelopmentCharges'])) {
 $developmentchargetitle = $_POST['developmentchargetitle'];
 $developmentchargetype = $_POST['developmentchargetype'];
 if ($developmentchargetype == "Others") {
  $developmentchargetype = $_POST['otherchargecategory'];
 } else {
  $developmentchargetype = $_POST['developmentchargetype'];
 }
 $developmentcharge = $_POST['developmentcharge'];
 $developmentchargepercentage = $_POST['developmentchargepercentage'];
 $developementchargeamount = $_POST['developementchargeamount'];
 $developmentchargedescription = SECURE(POST("developmentchargedescription"), "e");
 $bookingid = $_SESSION['BOOKING_VIEW_ID2'];
 $developmentchargecreatedat = RequestDataTypeDate;
 $developmentchargestatus = "OPEN";

 $Save = SAVE("developmentcharges", ["developmentchargepercentage", "bookingid", "developmentchargetitle", "developmentchargetype", "developmentcharge", "developementchargeamount", "developmentchargedescription", "developmentchargecreatedat", "developmentchargestatus"]);
 RESPONSE($Save, "Development Charges <b>$developmentchargetitle</b> with Booking <b>B$bookingid</b> is added Successfully!", "Unable to Add development charge in Bookings");

 //receive payments
} elseif (isset($_POST['ReceivedDevelopmentChargePayments'])) {

 //common variables
 $devchargepaymentamount = $_POST['devchargepaymentamount'];
 $devchargepaymentnotes = SECURE(POST("devchargepaymentnotes"), "e");
 $developmentchargeid = $_SESSION['DEVELOPMENT_CHARGE_ID'];
 $devchargepaymentmode = $_POST['devchargepaymentmode'];
 $devpaymentcreatedat = RequestDataTypeDate;

 //cash payments
 if ($devchargepaymentmode == "CASH") {
  $devpaymentreceivedby = $_POST['cashreceivername'];
  $devpaymentreleaseddate = date("Y-m-d", strtotime($_POST['cashreceivedate']));
  $devpaymentstatus = "RECEIVED";
  $devpaymentdetails = SECURE("Cash Received by $devpaymentreceivedby on $devpaymentreleaseddate", "e");

  //online payments
 } elseif ($devchargepaymentmode == "BANKING") {
  $onlinepaymenttype = $_POST['onlinepaymenttype'];
  $devpaymentbankname = $_POST['OnlineBankName'];
  $transactionId = $_POST['transactionId'];
  $devpaymentstatus = $_POST['transaction_status'];
  $devpaymentdetails = SECURE(POST("payment_details"), "e");
  $devpaymentreleaseddate = date("Y-m-d", strtotime($_POST['transactiondate']));
  $devpaymentdetails = "TxnID: $transactionId, Mode: $onlinepaymenttype, Notes: " . SECURE($devpaymentdetails, "d");
  $devpaymentdetails = SECURE($devpaymentdetails, "e");

  //cheque payments
 } else if ($devchargepaymentmode == "CHEQUE") {
  $checkissuedto = $_POST['checkissuedto'];
  $checknumber = $_POST['checknumber'];
  $devpaymentbankname = $_POST['BankName'];
  $ifsc = $_POST['ifsc'];
  $devpaymentreleaseddate = $_POST['checkissuedate'];
  $devpaymentstatus = $_POST['checkissustatus'];
  $devpaymentreceivedby = $_POST['chequereceivedby'];
  $devpaymentdetails = "CheckNo: $checknumber, IssuedTo: $checkissuedto, Bank: " . SECURE($devpaymentbankname, "d") . ", IFSC: $ifsc";
  $devpaymentdetails = SECURE($devpaymentdetails, "e");
 }

 $Save = SAVE("developmentchargepayments", ["developmentchargeid", "devchargepaymentmode", "devchargepaymentamount", "devchargepaymentnotes", "devpaymentreceivedby", "devpaymentbankname", "devpaymentreleaseddate", "devpaymentstatus", "devpaymentdetails", "devpaymentcreatedat"]);
 RESPONSE($Save, "Payment Received for Development Charge RefID: DC$developmentchargeid!", "Unable to recieve Payment for Development Charges!");
}