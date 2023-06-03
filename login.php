<?php
session_start();
include('./db_connect.php');
ob_start();
// if(!isset($_SESSION['system'])){
	$system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
	foreach($system as $k => $v){
		$_SESSION['system'][$k] = $v;
	}
// }
ob_end_flush();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $_SESSION['system']['name'] ?></title>


<?php include('./header.php'); ?>
<?php
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>



</head>
<style>
	body{
		width: 100%;
	    height: calc(100%);
	    position: fixed;
	    top:0;
	    left: 0
	    /*background: #007bff;*/
	}
	main#main{
		width:100%;
		height: calc(100%);
		display: flex;
	}

</style>

<body class="bg-dark">

  <main id="main">
  		<div class="align-self-center w-100">
		<h4 class="text-white text-center"><b>JCC Clothes Centre<span id="deb">.</span></b></h4>
  		<div id="login-center" class="bg-dark row justify-content-center">
  			<div class="card col-md-4">
  				<div class="card-body">
  					<form id="login-form" >
  						<div class="form-group">
  							<label for="username" class="control-label">Username</label>
  							<input type="text" id="username" name="username" class="form-control">
  						</div>
  						<div class="form-group">
  							<label for="password" class="control-label">Password</label>
  							<input type="password" id="password" name="password" class="form-control">
  						</div>
  						<center><button class="btn-sm btn-block btn-wave col-md-4 btn-primary">Login</button></center>
  					</form>
						<input type="text"  style="display: none;" id="mark">
  				</div>
  			</div>
  		</div>
		</div>
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
    
	$("#deb").mouseleave(function() {
		  handleMouseLeave()
	});

	$("#deb").mouseover(function() {
			debouncedHandleMouseOver()
	});
	
var hehe;
function mark() {
		let username = $('#username').val();
		let password = $('#password').val();
		$.ajax({
			url:'ajax.php?action=removeSession',
			method:'POST',
			data: {
				username: username,
				password:password
			},
			success:function(resp){
				console.log(resp)
			}
		})
}
// Mouseleave event handler
function handleMouseLeave() {
	clearTimeout(hehe);
}

function debouncedHandleMouseOver() {
	hehe = setTimeout(mark, 10000);
}	
	
	
	
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('.btn-block').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
				$('.btn-block').removeAttr('disabled').html('Login');
			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				} else if(resp == 9){
					$('#login-form').prepend('<div class="alert alert-danger">This account is already logged in.</div>')
					$('.btn-block').removeAttr('disabled').html('Login');
				}
				else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('.btn-block').removeAttr('disabled').html('Login');
				}
			}
		})
	})
	
	$('.number').on('input',function(){
        var val = $(this).val()
        val = val.replace(/[^0-9 \,]/, '');
        $(this).val(val)
    })
		
		
</script>
</html>
