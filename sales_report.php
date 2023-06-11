 <?php
    include 'db_connect.php';
		// $hehe = date('Y').'-W'.date('W');
    $min = isset($_GET['min']) ? $_GET['min'] : '2000-01-01';
    $month = isset($_GET['max']) ? $_GET['max'] : '2050-01-01';		
    // $week = isset($_GET['week']) ? $_GET['week'] :  date('Y').'-W'.date('W');
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
        <div class="card mt-2">
					<div class="card-header">
						<h3 class="text-center">Sales Report</h3>
						<div class=" mx-auto">
							<h5 class="ml-4">For the following period of:</h5>
							<form id="mark" class="d-flex align-items-center">
									<input class="ml-4 " type="date" id="min" name="min" value="<?php echo $min;?>">
									<input class="" type="date" id="max" name="max" value="<?php echo $month; ?>">
								<div class="ml-4">
									<button class="btn btn-primary btn-sm" type="submit">Filter</button>
									<button class="btn btn-info btn-sm" type="submit" id="clear">Refresh</button>
								</div>
							</form>
            </div>
					</div>
            <div class="card_body">
           
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
												$sales = $conn->query("SELECT * FROM sales s where  date_format(s.date_created,'%Y-%m-%d') between '$min' and '$month' order by unix_timestamp(s.date_created) asc ");
												if($sales->num_rows > 0):
												while($row = $sales->fetch_array()):
													$items = $conn->query("SELECT s.*,i.name,i.item_code as code,i.size, i.category  FROM stocks s inner join items i on i.id=s.item_id where s.id in ({$row['inventory_ids']}) ");
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
	
	$('#mark').submit(function(e) {
		e.preventDefault()
		location.replace('index.php?page=sales_report&min='+$('#min').val()+'&max='+$('#max').val())
	})
	$('#clear').click(function(e) {
		e.preventDefault()
		location.replace('index.php?page=sales_report')
	})
	
// $('#min').change(function(){
//     location.replace('index.php?page=sales_report&min='+$('#min').val())
// 	})
// 	$('#max').change(function(){
// 		location.replace('index.php?page=sales_report&max='+$('#max').val())
// })
$('#report-list').dataTable({
	searching: false
})
$('#print').click(function(){
            $('#report-list').dataTable().fnDestroy()
		var _c = $('#report-list').clone();
		var ns = $('noscript').clone();
            ns.append(_c)
		var nw = window.open('','_blank','width=900,height=600')
		nw.document.write('<p class="text-center"><b>Sales as of <?php echo date("F, Y",strtotime($month)) ?></b></p>')
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(() => {
			nw.close()
            $('#report-list').dataTable()
		}, 500);
	})
</script>