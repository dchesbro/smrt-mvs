<?php
	
	function upload_image() {
		
		// Set log variables
		global $upload_log;
		$upload_log = array();
		
		// Set function variables
		$usr_directory = 'img/usr/';
		$usr_image     = $usr_directory . basename( $_FILES['usr_image']['name'] );
		$usr_extension = pathinfo( $usr_image, PATHINFO_EXTENSION );
		
		if( !empty( $_FILES['usr_image']['tmp_name'] ) ) {
			$submit_ok = 1;
		
			// Check if image
			$img_check = getimagesize( $_FILES['usr_image']['tmp_name'] );
			
			if( $img_check == false ) {
				array_push( $upload_log, '<strong>ERROR:</strong> File is not an image.' );
				$submit_ok = 0;
			} else {
				array_push( $upload_log, '<strong>OK:</strong> File is an image (<code>' . $img_check['mime'] . '</code>).' );
			}
			
			// Check if already exists
			if( file_exists( $usr_image ) ) {
				array_push( $upload_log, '<strong>ERROR:</strong> File already exists.' );
				$submit_ok = 0;
			} else {
				array_push( $upload_log, '<strong>OK:</strong> File doesn\'t already exist.' );
			}
			
			// Check file size
			if( $_FILES['usr_image']['size'] > 500000 ) {
				array_push( $upload_log, '<strong>ERROR:</strong> File is too large (<code>' . $_FILES['usr_image']['size'] . ' bytes</code>).' );
				$submit_ok = 0;
			} else {
				array_push( $upload_log, '<strong>OK:</strong> File size within limit.' );
			}
			
			// Check if allowed format
			if( $usr_extension !== 'jpeg' && $usr_extension !== 'jpg' && $usr_extension !== 'png' && $usr_extension !== 'gif' ) {
				array_push( $upload_log, '<strong>ERROR:</strong> Sorry, only JPEG, JPG, PNG & GIF files are allowed.' );
				$submit_ok = 0;
			} else {
				array_push( $upload_log, '<strong>OK:</strong> File is allowed format.' );
			}
			
			// Check if errors, else upload
			if ( $submit_ok == 0 ) {
				array_push( $upload_log, '<strong>ERROR:</strong> File was not uploaded.' );
			} else {
				if ( move_uploaded_file( $_FILES['usr_image']['tmp_name'], $usr_image ) ) {
					array_push( $upload_log, '<strong>OK:</strong> File <code>' . basename( $_FILES['usr_image']['name'] ) . '</code> has been uploaded.' );
				} else {
					array_push( $upload_log, '<strong>ERROR:</strong> There was an error uploading your file.' );
				}
			}
		} else {
			array_push( $upload_log, '<strong>ERROR:</strong> Champions always select an image to upload!' );
		}
		return $usr_image;
		
	}
	
	function create_image( $usr_image, $smart_moves ) {
		
		$file = 'img/mvs/' . md5( $usr_image . $smart_moves[0] . $smart_moves[1] . $smart_moves[2] ) . '.jpg';
		
		// if ( !file_exists( $file ) ) {
			
			/*
				// Set background image
				$image = imagecreatefrompng( 'img/sm_green.png' );
				
				// Set font file and size
				$font_file = 'font/LTe50244.ttf';
				$size = '26';
				
				// Set text position
				$x_pos = 60;
				$y_pos = 245;

			*/
			
			// Set background image			
			$dst_image = 'img/sm_grey.png';
			
			// Set font file and size
			$font_file = 'font/tt0144m_.ttf';
			$size = '28';
			
			// Set text position
			$x_txt = 150;
			$y_txt = 310;
			
			// Get user image properties
			list( $src_width, $src_height, $src_type ) = getimagesize( $usr_image );
			
			switch ( $src_type ) {
				case IMAGETYPE_GIF:
					$src_image = imagecreatefromgif( $usr_image );
					break;
				case IMAGETYPE_JPEG:
					$src_image = imagecreatefromjpeg( $usr_image );
					break;
				case IMAGETYPE_PNG:
					$src_image = imagecreatefrompng( $usr_image );
					break;
			}
			
			// Set PIP dimensions
			define( 'PIP_WIDTH', 240 );
			define( 'PIP_HEIGHT', 223 );
			
			// Set aspect ratios
			$dst_ar = PIP_WIDTH / PIP_HEIGHT;
			$src_ar = $src_width / $src_height;
			
			if( $dst_ar < $src_ar ) {
				// User image wider
				$tmp_height = PIP_HEIGHT;
				$tmp_width = ( int )( PIP_HEIGHT * $src_ar );
			} else {
				// User image taller or same size
				$tmp_width = PIP_WIDTH;
				$tmp_height = ( int )( PIP_WIDTH / $src_ar );
			}
			
			// Create temp image
			$pip_image = imagecreatetruecolor( $tmp_width, $tmp_height );
			
			imagecopyresampled( $pip_image, $src_image, 0, 0, 0, 0, $tmp_width, $tmp_height, $src_width, $src_height );
			
			// Create background image
			$tmp_image = imagecreatefrompng( $dst_image );
			
			// Set PIP cropping boundaries
			$x_pip = ( $tmp_width - PIP_WIDTH ) / 2;
			$y_pip = ( $tmp_height - PIP_HEIGHT ) / 2;
			
			imagecopy( $tmp_image, $pip_image, 324, 23, $x_pip, $y_pip, PIP_WIDTH, PIP_HEIGHT );
			
			// Set text colors
			$black = imagecolorallocate( $tmp_image, 0, 0, 0 );
			$white = imagecolorallocate( $tmp_image, 240, 240, 230 );
			
			// Initialize line height
			$l = 0;
			$l_height = 50;
			
			foreach( $smart_moves as $move ) {
				// Draw drop shadow
				for( $s_depth = 0; $s_depth < 5; $s_depth = $s_depth + 1 ) {
					imagettftext( $tmp_image, $size, 0, $x_txt + $s_depth, $y_txt + $s_depth + $l, $black, $font_file, strtoupper( $move ) );
				}
				
				// Draw text
				imagettftext( $tmp_image, $size, 0, $x_txt, $y_txt + $l, $white, $font_file, strtoupper( $move ) );
				
				// Add 65px to line height for next line of text
				$l = $l + $l_height;
			}
			imagejpeg( $tmp_image, $file, 35 );
		// }
		return $file;
	}
	
	function validate_image( $usr_image ) {
		
		global $upload_log;
		
		$upload_log = array();
		
		$submit_ok = 1;
			
		// Check if user file is image
		$is_image = getimagesize( $usr_image['tmp_name'] );
		
		if( $is_image == false ) {
			array_push( $upload_log, '<strong>ERROR:</strong> File is not an image.' );
			$submit_ok = 0;
		} else {
			array_push( $upload_log, '<strong>OK:</strong> File is an image (<code>' . $is_image['mime'] . '</code>).' );
		}
		
		// Check if image extension is allowed
		$img_extension = pathinfo( sys_get_temp_dir () . basename( $usr_image['name'] ), PATHINFO_EXTENSION );
				
		if( $img_extension !== 'jpeg' && $img_extension !== 'jpg' && $img_extension !== 'png' && $img_extension !== 'gif' ) {
			array_push( $upload_log, '<strong>ERROR:</strong> Sorry, only JPEG, JPG, PNG & GIF files are allowed.' );
			$submit_ok = 0;
		} else {
			array_push( $upload_log, '<strong>OK:</strong> File is allowed format (<code>' . $img_extension . '</code>).' );
		}
		
		// Check if image size is within limit
		$size_limit = 500000;
		
		if( $usr_image['size'] > $size_limit ) {
			array_push( $upload_log, '<strong>ERROR:</strong> File is too large (<code>Limit is ' . $size_limit . ' bytes</code>).' );
			$submit_ok = 0;
		} else {
			array_push( $upload_log, '<strong>OK:</strong> File size within limit (<code>' . $_FILES['usr_image']['size'] . ' bytes</code>).' );
		}
		
		// Check if any errors, else return file
		if ( $submit_ok == 0 ) {
			array_push( $upload_log, '<strong>ERROR:</strong> File was not submitted.' );
		} else {
			return $usr_image['tmp_name'];
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	// Default moves
	$default_moves = array(
		'Accuracy',
		'Aggressiveness',
		'Agility',
		'Balance',
		'Body Control',
		'Control',
		'Coordination',
		'Cornering',
		'Decision Making',
		'Determination',
		'Endurance',
		'Fast Start',
		'Fearlessness',
		'Flat Out Speed',
		'Focus',
		'Getting There First',
		'Good Aim',
		'Grabbing It',
		'Holding On',
		'Leg Power',
		'Maneuverability',
		'Off The Block',
		'Peripheral Vision',
		'Push Off',
		'Quick Hands',
		'Quick Return',
		'Quick Start',
		'Quick Turn',
		'Reaction Time',
		'Release',
		'Return',
		'Rhythm',
		'Speed',
		'Spring',
		'Stick Handling',
		'Timing',
		'Toughness',
		'Versatility',
	);
	
	// Select 3 random moves from default
	$random_moves = array_rand( $default_moves, 3 );
	
	// Set default moves
	$smart_moves = array(
		'1. ' . $default_moves[$random_moves[0]],
		'2. ' . $default_moves[$random_moves[1]],
		'3. ' . $default_moves[$random_moves[2]]
	);
	
	// Check for user input
	if( isset( $_POST['submit'] ) ) {

		/*
		
			$error_log = array();
		
			if( strlen( $_POST['move_1'] ) == 0 ) {
				$error_log[] = 'Where\'s your first move?!';
			}
		
			if( strlen( $_POST['move_2'] ) == 0 ) {
				$error_log[] = 'Champions always enter a second move!';
			}
		
			if( strlen( $_POST['move_3'] ) == 0 ) {
				$error_log[] = 'Not so fast, you need a third move!';
			}
	
		*/
		
		$error_log = array();
				
		// Check for user image
		if( empty( $_FILES['usr_image']['tmp_name'] ) ) {					
			$error_log[] = '<strong>ERROR:</strong> No file selected.';
		} else {
			$usr_image = validate_image( $_FILES['usr_image'] );
		}

		// Check for user moves
		if( empty( $_POST['move_1'] ) || empty( $_POST['move_2'] ) || empty( $_POST['move_3'] ) ) {
			$error_log[] = '<strong>ERROR:</strong> Missing moves.';
		} else {
			$smart_moves = array(
				'1. ' . $_POST['move_1'],
				'2. ' . $_POST['move_2'],
				'3. ' . $_POST['move_3']
			);
		}
	}
	
	// Create image
	$filename = create_image( $usr_image, $smart_moves );
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>SMRT MVS</title>

		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<!-- Custom styles -->
		<link rel="stylesheet" href="css/styles.css">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		 
		<div class="container" style="margin: auto;">
			<div class="row">
				<div class="col-md-12">
			 
					<img src="<?=$filename;?>?id=<?=rand( 0, 1292938 );?>" class="img-responsive" alt="..." />
					
					<hr />
										 					
					<!-- <p>You can edit the image above by typing your details in below. It'll then generate a new image which you can right click on and save to your computer.</p> -->
					
					<?php					
						echo '<ul>';
						
						foreach( $upload_log as $upload_error ) {
							echo '<li>';
							
							echo $upload_error;
							
							echo '</li>';
						}
						
						echo '</ul>';
					?>
					
					<?php					
						echo '<ul>';
						
						foreach( $error_log as $error_message ) {
							echo '<li>';
							
							echo $error_message;
							
							echo '</li>';
						}
						
						echo '</ul>';
					?>
					
					<form enctype="multipart/form-data" method="post" style="text-align: center;">
						<div class="form-group">
							<input type="file" name="usr_image" id="usr_image">
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
					
				</div>
			</div>
		</div>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	
	</body>
</html>