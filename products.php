<?php include('db_connect.php');?>
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
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-product">
				<div class="card">
					<div class="card-header">
						   Product Form
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Item Code</label>
								<input type="text" class="form-control form-control-sm" readonly name="item_code">
								<small><i>Leave this blank to auto-generate a code.</i></small>
							</div>
							<div class="form-group">
								<label class="control-label">Name</label>
								<input type="text" class="form-control form-control-sm" name="name">
							</div>
							<div class="form-group">
								<label class="control-label">Category</label>
								<select name="category" id="category" class="custom-select custom-select-sm">
									<option value="" disabled selected>--Select Category--</option>
									<option>Male</option>
									<option>Female</option>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Description</label>
								<textarea name="description" id="description" cols="30" rows="4" class="form-control form-control-sm"></textarea>
							</div>
							<div class="form-group">
								<label class="control-label">Size</label>
								<select name="size" id="size" class="custom-select custom-select-sm">
									<option value="" disabled selected>--Select Size--</option>
									<option>EXTRA SMALL</option>
									<option>SMALL</option>
									<option>MEDIUM</option>
									<option>LARGE</option>
									<option>EXTRA LARGE</option>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Price</label>
								<input type="number" min='1' oninput="validity.valid||(value='');" class="form-control form-control-sm text-right" name="price">
							</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save </button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-product').get(0).reset()"> Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b>Product List</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Item Code</th>
									<th class="text-center">Product Info.</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$product = $conn->query("SELECT * FROM items order by id asc");
								while($row=$product->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p><b><?php echo $row['item_code'] ?></b></p>
									</td>
									<td class="">
										<p>Name: <b><?php echo $row['name'] ?></b></p>
										<p>Category: <b><?php echo $row['category']?></b></p>
										<p><small>Price: <b><?php echo "₱" . number_format($row['price'],2) ?></b></small></p>
										<p><small>Size: <b><?php echo $row['size'] ?></b></small></p>
										<p><small>Description: <b><?php echo $row['description'] ?></b></small></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_product" type="button" data-json='<?php echo json_encode($row) ?>'>Edit</button>
										<button class="btn btn-sm btn-danger delete_product" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>

</div>
<style>

	td{
		vertical-align: middle !important;
	}
	td p {
		margin:unset;
	}
	.custom-switch{
		cursor: pointer;
	}
	.custom-switch *{
		cursor: pointer;
	}
</style>
<script>
	$('#manage-product').on('reset',function(){
		$('input:hidden').val('')
		$('.select2').val('').trigger('change')
	})

	$('#manage-product').submit(function(e){
		e.preventDefault()
		start_load()
		
		var cat = $('#manage-product')
		let name =cat.find("[name='name']").val()
		let category =cat.find("[name='category']").val()
		let description =cat.find("[name='description']").val()
		let price =cat.find("[name='price']").val()
		let size =cat.find("[name='size']").val()
	

	if(name == "") {
		end_load()
		alert_toast("Please Fill All Fields",'danger')
        return;
	}	else if(category == null) {
		end_load()
		alert_toast("Please Fill All Fields",'danger')
        return;
	} else if(description == "") {
		end_load()
		alert_toast("Please Fill All Fields",'danger')
        return;
	} else if(size == null) {
		end_load()
		alert_toast("Please Fill All Fields",'danger')
        return;
	} else if(price == "") {
		end_load()
		alert_toast("Please Fill All Fields",'danger')
        return;
	} else {
		
		$.ajax({
			url:'ajax.php?action=save_product',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else if(resp==2){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
		
	})
	
	$('.edit_product').click(function(){
		start_load()
		var data = $(this).attr('data-json');
			data = JSON.parse(data)
			console.log(data)
		var cat = $('#manage-product')
		cat.get(0).reset()
		cat.find("[name='id']").val(data.id)
		cat.find("[name='item_code']").val(data.item_code)
		cat.find("[name='name']").val(data.name)
		cat.find("[name='category']").val(data.category)
		cat.find("[name='description']").val(data.description)
		cat.find("[name='price']").val(data.price)
		cat.find("[name='size']").val(data.size)
		end_load()
	})
	$('.delete_product').click(function(){
		_conf("Are you sure to delete this product?","delete_product",[$(this).attr('data-id')])
	})
	function delete_product($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_product',
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
	$('table').dataTable()
</script>
