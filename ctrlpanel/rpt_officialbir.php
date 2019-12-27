<?php 
include'config/db.php';
include'config/functions.php';
include'config/main_function.php';
include'config/DBController.php';
?>
<!DOCTYPE html>
<head>
<title>Sentinel Audit PC</title>
<!--title-->
    <link rel="shortcut icon" href="../img/icon.png" >
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <link href="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <link href="bower_components/bootstrap-social/bootstrap-social.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     <link rel="stylesheet" href="dist/css/skins/skin-green.css">
    <link rel="stylesheet" href="dist/css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <script src="jquery-ui/jquery-3.3.1.min.js"></script>
</head>

<body  class="hold-transition skin-green sidebar-mini">
  <div class="wrapper">
	
<style type="text/css">
#idletimeout { background:#CC5100; border:3px solid #FF6500; color:#fff; font-family:arial, sans-serif; text-align:center; font-size:12px; padding:10px; position:relative; top:0px; left:0; right:0; z-index:100000; display:none; }
#idletimeout a { color:#fff; font-weight:bold }
#idletimeout span { font-weight:bold }
.td {
   padding:0; margin:0;
}

#page {
   border-collapse: collapse;
}
.txtfield
{
  width:50%;
  height:34px;
}
.txt{
  width:25%;
  height:34px;
}
@media print {
  #printPageButton {
    display: none;
  }
}
</style>

<div id="idletimeout">
  You will be logged out in <span><!-- countdown place holder --></span>&nbsp;seconds due to inactivity. 
  <a id="idletimeout-resume" href="#">(Click here to continue using this page)</a>.
</div>

<!-- /navigation -->
    <div class="content-wrapper5" id = "content-wrapper5">
        


        <!-- Main content -->
        <section class="content">
          <div class="container" style="background:white; padding:1px;margin:1px;width:98%;">
          <h1><button id="sbutton" name="sbutton"><i class="fa fa-calendar"> </i></button> BIR Sales Summary Report</h1>
          <div id="parameters" name="parameters">
          <div id = "printPageButton">
          <p>In this section you can now search reports from the system.</p>
          </div>
          
          <p></p>
          <h3>Date Filter</h3>
          <form method="get">
          <input type="hidden" name="reports" class="btn btn-primary" value="1">
          From: <input type="date" name="from" class="txtfield"> <p></p>
          Until: <input type="date" name="until" class="txtfield">
          <p></p>
          <button class="btn btn-danger" id="searchbtn1" name="searchbtn1" value="Generate" onclick="submit_form()"><i class="fa fa-check"></i> Generate</button>
          </form>
          </div>
          
          <!-- End Search-->

          
          <?php 
          if(isset($_GET['searchbtn1'])){
            echo ('<script>
              $( "#sbutton" ).on( "click", function( event ) {
                $("#parameters").toggle(1500);
                $("#printPageButton").toggle(800);
                $("#main-header").toggle(800);
                $("#main-sidebar").toggle(800);
                $("#searchArea").toggle(800);
              });
                $("#parameters").toggle(1500);
                </script>');
            $from = filter($_GET['from']);//POST from date
            $until = filter($_GET['until']);//POST from Date
            $query = "SELECT DATE(datetimeOut) as datetimeOut, ROUND(SUM(todaysale),2) AS todaysale, ROUND(SUM(todaysGross),2) AS todaysGross, ROUND(SUM(vatablesale),2) as vatablesale, ROUND(SUM(12vat),2) as vat12, ROUND(SUM(vatExemptedSales), 2) as vatExempt, LPAD(MIN(beginOR),20,0) as beginOR, LPAD(MAX(endOR),20,0) as endOR, ROUND(MIN(oldGrand),2) as oldGrand, ROUND(MIN(newGrossTotal),2) as newGrossTotal, ROUND(MIN(oldGrossTotal),2) as oldGrossTotal, ROUND(MAX(newGrand),2) as newGrand, SUM(discounts) as discounts, ABS(SUM(voids)) as voids, zCount FROM zread.main WHERE DATE(DateTimeOUT) BETWEEN '$from' AND '$until' GROUP BY DATE(datetimeOut) ORDER BY terminalnum, datetimeOut DESC";
            $cash = getdata_inner_join($query); //query for getting the results
            //echo $query;  
            $_SESSION['from'] = $from;
            $_SESSION['until'] = $until;
            //$query1 = "SELECT * FROM cash INNER JOIN exit_tbl on cash.cashierName = exit_tbl.CashierName WHERE DATE(loginDate) BETWEEN '$from' AND '$until'";
            //$list = getdata_inner_join($query1);//query for getting the results
          ?>
          
          <!-- Search for list of employee-->
          <div id = "searchArea" name = "searchArea">
          <b>CHINESE GENERAL HOSPITAL AND MEDICAL CTR</b><br>
                     286 BLUMENTRITT ST. STA. CRUZ MANILA<br>
                     TIN: 000-328-853-000<br>
                     <b>Reporting Period for POS: EX01</b>
          </div>
          <table class="table-border" width="100%" style="padding: 2px"> 
            <tr style="font-size: 8px;">
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td colspan="5" style="background-color: rgb(180,180,180); text-align: center; padding: 2px">Deductions</td>
              <td colspan="5" style="background-color: rgb(180,180,180); text-align: center; padding: 2px">Adjustments on VAT</td>
              <td></td>
            </tr>
            <tr style="font-size: 9px; text-align: center;">
              <td><b>Date</b></td>
              <td><b>Beginning<br> OR No.</b></td>
              <td><b>Ending<br> OR No.</b></td>
              <td><b>Grand<br>Accum.<br>Sales<br>Ending</b></td>
              <td><b>Grand<br>Accum.<br>Sales<br>Beginning</b></td>
              <td><b>Gross<br>Sales<br>for<br>the Day</b></td>
              <td><b>Sales<br>Issued<br>w Manual<br>SI/OR</b></td>
              <td><b>Gross<br>Sales<br>From<br>POS</b></td>
              <td><b>VATable<br>Sales</b></td>
              <td><b>VAT<br>Amount</b></td>
              <td><b>VAT-Exempt<br>Sales</b></td>
              <td><b>Zero<br>Rated<br>Sales</b></td>
              <td><b>Regular<br>Discount</b></td>
              <td><b>Special<br>Discount<br>SC/PWD</b></td>
              <td><b>Returns</b></td>
              <td><b>Void</b></td>
              <td><b>Total Deductions</b></td>

              <td><b>VAT<br>on<br>Special<br>Discounts</b></td>
              <td><b>VAT<br>on<br>Returns</b></td>
              <td><b>Others</b></td>
              <td><b>Total<br>VAT<br>Adj.</b></td>
              
              <td><b>VAT<br>Payable</b></td>
              <td><b>Net<br>Sales</b></td>
              <td><b>Other<br>Income</b></td>
              <td><b>Sales<br>Overrun/<br>Overflow</b></td>
              <td><b>Total<br>Net<br>Sales</b></td>
              <td><b>Z-<br>Count</b></td>
              <td><b>Remarks</b></td>
              
            </tr>
            
            <?php if(!empty($cash)):?>
            <?php foreach ($cash as $key => $value):?>
             <tr style="font-size: 10px; text-align: center; padding: 2px">
              <td><?php echo $value->datetimeOut?></td>
              <td><?php echo "EX01" . $value->beginOR?></td>
              <td><?php echo "EX01" . $value->endOR?></td>
              <td><?php echo $value->newGrossTotal?></td>
              <td><?php echo $value->oldGrossTotal?></td>
              <td><?php echo $value->newGrossTotal - $value->oldGrossTotal?></td>
              <td>-</td>
              <td><?php echo $value->newGrossTotal - $value->oldGrossTotal?></td>
              <td><?php echo $value->vatablesale?></td>
              <td><?php echo $value->vat12?></td>
              <td><?php echo $value->vatExempt?></td>
              <td>0.00</td>
              <td>0.00</td>              
              <td><?php echo round($value->discounts,2)?></td>
              <td>0.00</td>
              <td>0.00</td>               
              <td><?php echo round($value->discounts,2)?></td>

              <td><?php echo round($value->vatExempt * 0.12,2)?></td>  
              <td>0.00</td>
              <td>0.00</td>
              <td><?php echo round($value->vatExempt * 0.12,2)?></td>   
              <td><?php echo $value->vat12?></td>
              <td><?php echo round($value->todaysale,2)?></td>
              <td>0.00</td>
              <td>0.00</td>              
              <td><?php echo round($value->todaysale,2)?></td>
              <td><?php echo $value->zCount?></td>
              <td></td>
            </tr>
            <?php endforeach;?>
          <?php else:?>
          There are no records on the database
        <?php endif;?>
        <tr><td></td></tr>
        
          </table>
          <div class="btn">
            <form action="" method="post">
                  <button type="submit" id="export_offbir"
                    name='export_offbir' value="Export to Excel"
                    class="btn btn-info"><span class="glyphicon glyphicon-circle-arrow-down"></span> Export to Excel</button>
            </form>
          </div>
          <!-- Exporting
          <a href="excel.php?from=<?php echo $_GET['from']?>&until=<?php echo $_GET['until']?>" id = "printPageButton" class="btn btn-danger"> Export to Excel/CSV</a>-->
          <a href="" id = "printPageButton" onClick="window.print();" class="btn btn-primary"><span class="glyphicon glyphicon-paperclip"></span> Export to PDF file</a>
          <!--- End of exporting-->
<?php 
}
?>

         
          
          
          </div>      
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
<!-- Main Footer -->
</div><!--/page-wrapper(content) -->




</body>
    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.js"></script>
    <!-- DataTables JavaScript -->
    <script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <!-- <script src="../dist/js/sb-admin-2.js"></script> -->

    
    
    <!-- AdminLTE App -->
    <script src="dist/js/app.js"></script>
    <!--idle scripts-->
    <script src="dist/js/jquery.idletimer.js" type="text/javascript"></script>
    <script src=".dist/js/jquery.idletimeout.js" type="text/javascript"></script>

