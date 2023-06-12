<?php include 'db_connect.php' ?>

<style>
   span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    top: 0;
}
.imgs{
		margin: .5em;
		max-width: calc(100%);
		max-height: calc(100%);
	}
	.imgs img{
		max-width: calc(100%);
		max-height: calc(100%);
		cursor: pointer;
	}
	#imagesCarousel,#imagesCarousel .carousel-inner,#imagesCarousel .carousel-item{
		height: 60vh !important;background: black;
	}
	#imagesCarousel .carousel-item.active{
		display: flex !important;
	}
	#imagesCarousel .carousel-item-next{
		display: flex !important;
	}
	#imagesCarousel .carousel-item img{
		margin: auto;
	}
	#imagesCarousel img{
		width: auto!important;
		height: auto!important;
		max-height: calc(100%)!important;
		max-width: calc(100%)!important;
	}
</style>


<div class="container-fluid">
	
	<div class=" p-5 align-center m-3 rounded bg-info" style="max-height: 100%">
		<div class="row justify-content-center h-100" >
      <h2 class="text-light "><b>DASHBOARD ₱</b></h2>
			<div class="row p-0 col-12">
        <div class="col-lg-4 p-2">
					<div class=" d-flex flex-column align-items-center justify-content-center h-100 p-3 bg-light" style="border-radius: 20px;">
						<h4>Current Month Income</h4>
						<?php
								$date = date("Y-m");
								$total = 0;
								$sales = $conn->query("SELECT * FROM sales s where s.amount_tendered > 0 and date_format(s.date_created,'%Y-%m') = '{$date}' order by unix_timestamp(s.date_created) asc ");
								if($sales->num_rows > 0) {
								while($row = $sales->fetch_array()) {
									$items = $conn->query("SELECT s.*,i.name,i.item_code as code,i.size  FROM stocks s inner join items i on i.id=s.item_id where s.id in ({$row['inventory_ids']})");
								 while($roww = $items->fetch_array()) {
									 $total += $roww['price']*$roww['qty'];
								 }
								}
							 }
              ?>
						  <h4 class="text-success"><strong><?php echo "₱" . number_format($total,2)?></strong></h4>
					</div>
				</div>
				<div class="col-lg-4 p-2">
					<div class="d-flex flex-column align-items-center justify-content-center h-100 p-3 bg-light" style="border-radius: 20px;">
					<h4>Transaction Count</h4>
						<?php
							$totals = 0;
							$sales = $conn->query("SELECT * FROM sales s where s.amount_tendered > 0 and date_format(s.date_created,'%Y-%m') = '{$date}' order by unix_timestamp(s.date_created) asc ");
							if($sales->num_rows > 0) {
							while($row = $sales->fetch_array()) {
								$totals++;
							}
							}
						?>
						<div class=" w-100 justify-content-around d-flex flex-row">
							<h6>Total of:</h6>
							<h4 class=""><strong class="text-success"><?php echo $totals; ?></strong></h4>
						</div>
					</div>
				</div>
				<div class="col-lg-4 p-2">
				<div class=" d-flex flex-column align-items-center justify-content-center h-100 p-3 bg-light" style="border-radius: 20px;">
							<h4>Total of Stocks</h4>
							<?php
							$haha = 0;
							$i = 1;
							$qry = $conn->query("SELECT * FROM items order by name asc");
							while($row=$qry->fetch_assoc()){
								$inn = $conn->query("SELECT sum(qty) as stock FROM stocks where type = 1 and item_id =".$row['id']);
								$inn = $inn->num_rows > 0 ? $inn->fetch_array()['stock'] :0 ;
								$out = $conn->query("SELECT sum(qty) as stock FROM stocks where type = 2 and item_id =".$row['id']);
								$out = $out->num_rows > 0 ? $out->fetch_array()['stock'] :0 ;
								$available = $inn - $out;
								if($available > 0) {
									$haha += $available;
									// continue;
								}
							}
								?>
								<h4 class="text-success"><strong><?php echo number_format($haha,0)?></strong></h4>
						</div>
				</div>
			</div>
			<div class="row p-2 col-12">
				<div class="bg-light w-100 p-3" style="border-radius: 20px;">
					<div id="resizable" style="height: 370px;border:1px solid gray;">
					<div id="chartContainer" style="height: 370px; width: 100%;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	
	$(document).ready(function() {
		var chart = new CanvasJS.Chart("chartContainer", 
    {
			animationEnabled: true,
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			title:{
				text: "Sales Chart"
			},
			axisX: {
					reversed: true
				},
			data: [{
				type: "column",
				showInLegend: true,
				legendMarkerColor: "grey",
				dataPoints: [
					
					<?php
          
						$dateTime = new DateTime();
						for ( $i = 1; $i <= 6; $i++ )
            {
              
							$total = 0;
							$sales = $conn->query("SELECT * FROM sales s where s.amount_tendered > 0 and date_format(s.date_created,'%Y-%m') = '{$dateTime->format('Y-m')}' order by unix_timestamp(s.date_created) asc ");
							if( $sales->num_rows > 0 ){
								while( $row = $sales->fetch_array() )
                {
									$items = $conn->query("SELECT s.*,i.name,i.item_code as code,i.size  FROM stocks s inner join items i on i.id=s.item_id where s.id in ({$row['inventory_ids']})");
									while( $roww = $items->fetch_array() )
                  {
										$total += $roww['price'] * $roww['qty'];
									}
								}
							}
						
							echo '{ y: '.$total.', label: "'.$dateTime->format('F Y').'" },';
							$dateTime->modify('-1 month');
						}
					?>
				]
			}]
		});
		chart.render();
	})

		// window.onload = function () {
		
		// }
		$('#manage-records').submit(function(e){
					e.preventDefault()
					start_load()
					$.ajax({
							url:'ajax.php?action=save_track',
							data: new FormData($(this)[0]),
							cache: false,
							contentType: false,
							processData: false,
							method: 'POST',
							type: 'POST',
							success:function(resp){
									resp=JSON.parse(resp)
									if(resp.status==1){
											alert_toast("Data successfully saved",'success')
											setTimeout(function(){
													location.reload()
											},800)

									}

							}
					})
			})
			$('#tracking_id').on('keypress',function(e){
					if(e.which == 13){
							get_person()
					}
			})
			$('#check').on('click',function(e){
							get_person()
			})
			function get_person(){
							start_load()
					$.ajax({
									url:'ajax.php?action=get_pdetails',
									method:"POST",
									data:{tracking_id : $('#tracking_id').val()},
									success:function(resp){
											if(resp){
													resp = JSON.parse(resp)
													if(resp.status == 1){
															$('#name').html(resp.name)
															$('#address').html(resp.address)
															$('[name="person_id"]').val(resp.id)
															$('#details').show()
															end_load()

													}else if(resp.status == 2){
															alert_toast("Unknow tracking id.",'danger');
															end_load();
													}
											}
									}
							})
 			 }
</script>
