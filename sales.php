<?php include('db_connect.php');?>
<?php
    include 'db_connect.php';
    $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
?>
<style>
	input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.3); /* IE */
  -moz-transform: scale(1.3); /* FF */
  -webkit-transform: scale(1.3); /* Safari and Chrome */
  -o-transform: scale(1.3); /* Opera */
  transform: scale(1.3);
  padding: 10px;
  cursor:pointer;
}
</style>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">

			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Sales List</b>
					</div>
					<div class="card-body">
						<table id="my-table" class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Date</th>
									<th class="">Amount</th> 
									<th class="">User</th>
									<!-- added by ace -->
									<?php if($_SESSION['login_type'] != 2): ?>
										<th class="text-center">Action</th>
									<?php endif; ?>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$order = $conn->query("SELECT * FROM sales order by unix_timestamp(date_created) desc ");
								while($row=$order->fetch_assoc()):
                  	$orders = $conn->query("SELECT * FROM users where id = {$row['user_id']} ");
								  	while($rows=$orders->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td>
										<p> <b><?php echo date("M d,Y",strtotime($row['date_created'])) ?></b></p>
									</td>
									<td>
										<p class="text-right"> <b><?php echo number_format($row['total_amount'],2) ?></b></p>
									</td>
                	<td>
										<p class="text-right"> <b><?php echo $rows['name'] ?></b></p>
									</td>
										<!-- added by ace -->
									<?php if($_SESSION['login_type'] != 2): ?>
										<td class="text-center">
											<button class="btn btn-sm btn-outline-danger delete_order" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
										</td>
									<?php endif; ?>
								</tr>
                	<?php endwhile; ?>
								<?php endwhile; ?>
							</tbody>
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
			<!-- Table Panel -->
		</div>
	</div>

</div>
<noscript>
	<style>
		table#myTable{
			width:100%;
			border-collapse:collapse
		}
		table#myTable td,table#myTable th{
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
		td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height:150px;
	}
	</style>
</noscript>
<style>


</style>
<script>
	$(document).ready(function(){
	})
	$('#new_order').click(function(){
		uni_modal("New order ","manage_order.php","mid-large")

	})
	$('.view_order').click(function(){
		uni_modal("Order  Details","view_order.php?id="+$(this).attr('data-id'),"mid-large")

	})
	$('.delete_order').click(function(){
		_conf("Are you sure to delete this order ?","delete_order",[$(this).attr('data-id')])
	})
	function delete_order($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_order',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	
	$('#my-table').dataTable({searching:false})
	$('#print').click(function(){
            $('#my-table').dataTable().fnDestroy()
		var _c = $('#my-table').clone();
		var ns = $('noscript').clone();
            ns.append(_c)
		var nw = window.open('','_blank','width=900,height=600')
		nw.document.write('<p class="text-center"><b>Inventory Report as of <?php echo date("F, Y",strtotime($month)) ?></b></p>')
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(() => {
			nw.close()
            $('#my-table').dataTable()
		}, 500);
	})
</script>
