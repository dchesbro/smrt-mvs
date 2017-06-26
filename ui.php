<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>SMRT MVS</title>

		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
		
		<!-- Custom styles -->
		<link rel="stylesheet" href="css/styles_ui.css">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		
		<form enctype="multipart/form-data" method="post" class="mvs-form">
			
			<img src="/img/sm_grey.png" class="mvs-bg" />
			
			<label for="usr_image">
				<span class="v-center">
					<i class="fa fa-3x fa-picture-o" aria-hidden="true"></i>
					Choose an image
				</span>
			</label>
			
			<input type="file" name="usr_image" id="usr_image">
			
			<div class="mvs-group">
				<input value="" type="text" placeholder="<?php if( !isset( $_POST['move_1'] ) ) { echo $default_moves[$random_moves[0]]; } ?>" name="move_1" maxlength="20" />
				<input value="" type="text" placeholder="<?php if( !isset( $_POST['move_2'] ) ) { echo $default_moves[$random_moves[0]]; } ?>" name="move_2" maxlength="20" />
				<input value="" type="text" placeholder="<?php if( !isset( $_POST['move_3'] ) ) { echo $default_moves[$random_moves[0]]; } ?>" name="move_3" maxlength="20" />
			</div>
			
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">1.</div>
					<input value="" type="text" placeholder="<?php if( !isset( $_POST['move_1'] ) ) { echo $default_moves[$random_moves[0]]; } ?>" name="move_1" maxlength="20" class="form-control input-lg">
				</div>
			</div>
			
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">2.</div>
					<input value="" type="text" placeholder="<?php if( !isset( $_POST['move_2'] ) ) { echo $default_moves[$random_moves[1]]; } ?>" name="move_2" maxlength="20" class="form-control input-lg">
				</div>
			</div>
			
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">3.</div>
					<input value="" type="text" placeholder="<?php if( !isset( $_POST['move_3'] ) ) { echo $default_moves[$random_moves[2]]; } ?>" name="move_3" maxlength="20" class="form-control input-lg">
				</div>
			</div>
			
			<input value="Take it to Mo!" type="submit" name="submit" class="btn btn-default" />
			
		</form>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	
	</body>
</html>