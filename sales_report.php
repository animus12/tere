 <?php
    include 'db_connect.php';
		$hehe = date('Y').'-W'.date('W');
    $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
    $week = isset($_GET['week']) ? $_GET['week'] :  $hehe;
?>
<?php
	if($_SESSION['login_type'] != 1) {
		?>
			<script>
				window.location.href = "index.php?page=home"
			</script>
		<?php
	}
 ?>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card_body">
            <div class="row container-lg pt-4">
							<div class="col-6 d-flex justify-content-center">
                <label for="" class="mt-2">Sales Report for the month of:</label>
                <div class="col-sm-6">
                    <input type="month" name="month" id="month" value="<?php echo $month ?>" class="form-control">
                </div>
							</div>
							<div class="col-6 d-flex justify-content-center">
								<label for="" class="mt-2">Sales Report for the week of:</label>
                <div class="col-sm-6">
                    <input type="week" name="week" id="week"  value="<?php echo $week;?>" class="form-control">
									</div>
							</div>
            </div>
            <hr>
            <div class="col-md-12">
                <table class="table table-bordered" id='report-list'>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="">Date</th>
                            <th class="">Item Code</th>
                            <th class="">Item Name</th>
                            <th class="">Size</th>
                            <th class="">Category</th>
                            <th class="">Price</th>
                            <th class="">QTY</th>
                            <th class="">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
											<?php
											
											$i = 1;
											$total = 0;
											if(isset($month)) {
												$sales = $conn->query("SELECT * FROM sales s where s.amount_tendered > 0 and date_format(s.date_created,'%Y-%m') = '$month' order by unix_timestamp(s.date_created) asc");
											}
											if(isset($week)) {
													preg_match('/(\d{4})-W(\d{2})/', $week, $matches);
													$year = $matches[1];
													$weekNumber = $matches[2];
													$sales = $conn->query("SELECT * FROM sales s where YEARWEEK(s.date_created) = '{$year}{$weekNumber}'");
												}
												if($sales->num_rows > 0):
												while($row = $sales->fetch_array()):
													$items = $conn->query("SELECT s.*,i.name,i.item_code as code,i.size, i.category  FROM stocks s inner join items i on i.id=s.item_id where s.id in ({$row['inventory_ids']})");
													while($roww = $items->fetch_array()):
														$total += $roww['price']*$roww['qty'];
											?>
			          		<tr>
                        <td class="text-center"><?php echo $i++ ?></td>
                        <td>
                            <p> <b><?php echo date("M d,Y",strtotime($row['date_created'])) ?></b></p>
                        </td>
                        <td>
                            <p class="text-right"> <b><?php echo $roww['code'] ?></b></p>
                        </td>
                        <td>
                            <p class="text-right"> <b><?php echo $roww['name'] ?></b></p>
                        </td>
                        <td>
                            <p class="text-right"> <b><?php echo $roww['size'] ?></b></p>
                        </td>
												<!-- for development -->
                        <td>
                            <p class="text-right"> <b><?php echo $roww['category'] ?></b></p>
                        </td>
                        <td>
                            <p class="text-right"> <b><?php echo number_format($roww['price'],2) ?></b></p>
                        </td>
                        <td>
                            <p class="text-right"> <b><?php echo $roww['qty'] ?></b></p>
                        </td>
                        <td>
                            <p class="text-right"> <b><?php echo number_format($roww['price']*$roww['qty'],2) ?></b></p>
                        </td>
                    </tr>
											<?php
													endwhile;
											endwhile;
													else:
											?>
                    <tr>
												<th class="text-center" colspan="9">No Data.</th>
                    </tr>
											<?php
													endif;
											?>
			        </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="8" class="text-right">Total</th>
                            <th class="text-right"><?php echo number_format($total,2) ?></th>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <div class="col-md-12 mb-4">
                    <center>
                        <button class="btn btn-success btn-sm col-sm-3" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                    </center>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<noscript>
	<style>
		table#report-list{
			width:100%;
			border-collapse:collapse
		}
		table#report-list td,table#report-list th{
			border:1px solid
		}
		p {
				margin:unset;
		}
		.text-center{
			text-align:center
		}
		.text-right{
				text-align:right
		}
	</style>
</noscript>
<script>
$('#month').change(function(){
    location.replace('index.php?page=sales_report&month='+$(this).val())
	})
	$('#week').change(function(){
		location.replace('index.php?page=sales_report&week='+$(this).val())
})
$('#report-list').dataTable()
$('#print').click(function(){
            $('#report-list').dataTable().fnDestroy()
		var _c = $('#report-list').clone();
		var ns = $('noscript').clone();
            ns.append(_c)
		var nw = window.open('','_blank','width=900,height=600')
		nw.document.write('<p class="text-center"><b>Sales Report as of <?php echo date("F, Y",strtotime($month)) ?></b></p>')
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(() => {
			nw.close()
            $('#report-list').dataTable()
		}, 500);
	})
</script>