<table style="text-align:left;line-height: 17px;">
  <tr>
    <th style="width:35%;">REF, CRN No : </th>
    <td><?php echo $crn_no . "/" . $ref_no; ?></td>
  </tr>
  <tr>
    <th style="width:35%;">Customer ID : </th>
    <td>CUST00<?php echo $customer_id; ?></td>
  </tr>
  <tr>
    <th>Customer Name :</th>
    <td><?php echo $customer_name; ?></td>
  </tr>
  <tr>
    <th>Address :</th>
    <td><?php echo "$user_street_address $user_area_locality $user_city $user_state $user_pincode $user_country"; ?></td>
  </tr>
  <tr>
    <th>Phone Number :</th>
    <td><?php echo $customer_phone; ?></td>
  </tr>
  <tr>
    <th>Email ID :</th>
    <td><?php echo $customer_email; ?></td>
  </tr>


</table>