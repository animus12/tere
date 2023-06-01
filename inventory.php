<?php include "db_connect.php" ?>
<?php
    include 'db_connect.php';
    $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
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

<div class="col-lg-12">
	<div class="card">
		<div class="card-header"><b>Inventory</b></div>
		<div class="card-body">
			<table class="table table-bordered" id="myTable">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Item Code</th>
						<th class="text-center">Item Name</th>
						<th class="text-center">Item Size</th>
						<th class="text-center">Item Category</th>
						<th class="text-center">Stock Available</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						$qry = $conn->query("SELECT * FROM items order by name asc");
						while($row=$qry->fetch_assoc()):
							$inn = $conn->query("SELECT sum(qty) as stock FROM stocks where type = 1 and item_id =".$row['id']);
							$inn = $inn->num_rows > 0 ? $inn->fetch_array()['stock'] :0 ;
							$out = $conn->query("SELECT sum(qty) as stock FROM stocks where type = 2 and item_id =".$row['id']);
							$out = $out->num_rows > 0 ? $out->fetch_array()['stock'] :0 ;
							$available = $inn - $out;
					?>
					<tr>
						<td><?php echo $i++ ?></td>
						<td><?php echo $row['item_code'] ?></td>
						<td><?php echo ucwords($row['name']) ?></td>
						<td><?php echo $row['size'] ?></td>
						<td><?php echo $row['category'] ?></td>
						<?php if($available <= 0): ?>
							<td class="bg-danger text-light text-center">Out of Stock</td>
						<?php elseif($available <= 50): ?>
								<td class="bg-warning text-center"><?php echo $available ?></td>
						<?php else: ?>
									<td class="text-center"><?php echo $available ?></td>
						<?php endif; ?>
						<!-- <td class="text-center <?php echo ($available < 100)? "bg-warning": "" ?> <?php echo ($available <= 0)? "bg-danger text-light": "" ?>"><?php echo (number_format($available) <= 0)? "Out of Stock": number_format($available) ?></td> -->
					</tr>
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
	</style>
</noscript>
<script>
	$(document).ready(function() {
		// var table = $('#myTable').DataTable( {
    //     lengthChange: true,
    //     buttons: [ 'copy', 'excel', 'pdf', 'colvis' ]
    // } );
	})
	
	$('#myTable').dataTable()
	$('#print').click(function(){
            $('#myTable').dataTable().fnDestroy()
		var _c = $('#myTable').clone();
		var ns = $('noscript').clone();
            ns.append(_c)
		var nw = window.open('','_blank','width=900,height=600')
		nw.document.write('<p class="text-center"><b>Inventory Report as of <?php echo date("F, Y",strtotime($month)) ?></b></p>')
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(() => {
			nw.close()
            $('#myTable').dataTable()
		}, 500);
	})
</script>