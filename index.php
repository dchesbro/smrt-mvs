<?php
	
	function get_default_template() {
		
		// Set templates
		$templates = array( 'grey', 'green' );
		
		// Get template option
		$template_option = $_GET['t'];
		
		// Check if option set or invalid
		if( empty( $template_option ) || !in_array( $template_option, $templates ) ) {
			$template_option = $templates[1];
		}
		return $template_option;
		
	}
	
	function get_default_moves() {
		
		// Set default moveset
		$default_moveset = array(
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
		$random_moves = array_rand( $default_moveset, 3 );
		
		// Set default moves
		$default_moves = array(
			$default_moveset[$random_moves[0]],
			$default_moveset[$random_moves[1]],
			$default_moveset[$random_moves[2]]
		);
		return $default_moves;
		
	}			
	
	function validate_image( $user_image ) {
		
		global $image_log;
		
		$image_log = array();
				
		// Initialize validation and check if user file present
		$submit_ok = true;
		
		if( empty( $user_image['tmp_name'] ) ) {					
			$image_log[] = '<strong>ERROR:</strong> Champions always choose an image!';
			return false;
		} else {
			$image_log[] = '<strong>OK:</strong> File was selected.';
		}
			
		// Check if user file is image
		$is_image = getimagesize( $user_image['tmp_name'] );
		
		if( $is_image == false ) {
			$image_log[] = '<strong>ERROR:</strong> File is not an image.';
			$submit_ok = false;
		} else {
			$image_log[] = '<strong>OK:</strong> File is an image (' . $is_image['mime'] . ').';
		}
		
		// Check if image extension is allowed
		$allowed_extensions = array( 'JPEG', 'JPG', 'PNG', 'GIF', 'jpeg', 'jpg', 'png', 'gif' );
		
		$image_extension = pathinfo( sys_get_temp_dir () . basename( $user_image['name'] ), PATHINFO_EXTENSION );
				
		if( !in_array( $image_extension, $allowed_extensions ) ) {
			$image_log[] = '<strong>ERROR:</strong> File must be a JPG, PNG or GIF image.';
			$submit_ok = false;
		} else {
			$image_log[] = '<strong>OK:</strong> File is an allowed format (' . $image_extension . ').';
		}
		
		// Check if image size is within limit
		$size_limit = 5242880;
		
		if( $user_image['size'] > $size_limit ) {
			$image_log[] = '<strong>ERROR:</strong> File is too large (Limit is ' . $size_limit . ' bytes).';
			$submit_ok = false;
		} else {
			$image_log[] = '<strong>OK:</strong> File size within limit (' . $user_image['size'] . ' bytes).';
		}
		
		// Check if any errors, else return true
		if ( $submit_ok == false ) {
			$image_log[] = '<strong>ERROR:</strong> File was not submitted.';
			return false;
		} else {
			$image_log[] = '<strong>OK:</strong> File submitted successfully.';
			return true;
		}
		
	}
	
	function validate_moves( $move_1, $move_2, $move_3 ) {
		
		global $moves_log;
		
		$moves_log = array();
		
		// Check for user moves		
		if( empty( $move_1 ) || empty( $move_2 ) || empty( $move_3 ) ) {
			$moves_log[] = '<strong>ERROR:</strong> Champions always enter three moves!';
			return false;
		} else {
			$moves_log[] = '<strong>OK:</strong> Moves submitted successfully.';
			return true;
		}
		
	}
	
	function create_image( $template_option, $user_moves, $user_image ) {
		
		// $file = 'img/mvs/' . md5( $user_image . $user_moves[0] . $user_moves[1] . $user_moves[2] ) . '.jpg';
		
		$file = 'img/mvs/debug.jpg';
		
		if ( !file_exists( $file ) ) {
			
			// Set template options
			switch ( $template_option ) {
				case 'grey':
			        // Set background image
					$dst_image = imagecreatefrompng( 'img/sm_grey.png' );
					
					// Set font file and size
					$font_file = 'font/tt0144m_.ttf';
					$font_size = '28';
					
					// Set text position
					$x_txt = 150;
					$y_txt = 310;
			        break;
				case 'green':
			        // Set background image
					$dst_image = imagecreatefrompng( 'img/sm_green.png' );
					
					// Set font file and size
					$font_file = 'font/LTe50244.ttf';
					$font_size = '26';
					
					// Set text position
					$x_txt = 60;
					$y_txt = 245;
			        break;
			}
			
			if( $template_option == 'grey' ) {
				// Get user image properties
				list( $src_width, $src_height, $src_type ) = getimagesize( $user_image );
				
				switch ( $src_type ) {
					case IMAGETYPE_GIF:
						$src_image = imagecreatefromgif( $user_image );
						break;
					case IMAGETYPE_JPEG:
						$src_image = imagecreatefromjpeg( $user_image );
						break;
					case IMAGETYPE_PNG:
						$src_image = imagecreatefrompng( $user_image );
						break;
				}
				
				// Check image orientation				
				$exif = exif_read_data( $user_image );
				
				switch ( $exif['Orientation'] ) {
					case 3:
						$src_image = imagerotate( $src_image, 180, 0 );
						break;
					case 6:
						$src_image = imagerotate( $src_image, -90, 0 );
						break;
					case 8:
						$src_image = imagerotate( $src_image, 90, 0 );
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
				
				// Set PIP cropping boundaries
				$x_pip = ( $tmp_width - PIP_WIDTH ) / 2;
				$y_pip = ( $tmp_height - PIP_HEIGHT ) / 2;
				
				imagecopy( $dst_image, $pip_image, 324, 23, $x_pip, $y_pip, PIP_WIDTH, PIP_HEIGHT );
			}
			
			// Set text colors
			$black = imagecolorallocate( $dst_image, 0, 0, 0 );
			$white = imagecolorallocate( $dst_image, 240, 240, 230 );
			
			// Initialize line height
			$l = 0;
			$l_height = 50;
			
			// Initialize move number
			$n = 1;
			
			foreach( $user_moves as $move ) {
				// Draw drop shadow
				for( $s_depth = 0; $s_depth < 5; $s_depth = $s_depth + 1 ) {
					imagettftext( $dst_image, $font_size, 0, $x_txt + $s_depth, $y_txt + $s_depth + $l, $black, $font_file, strtoupper( $n . '. ' . $move ) );
				}
				// Draw text
				imagettftext( $dst_image, $font_size, 0, $x_txt, $y_txt + $l, $white, $font_file, strtoupper( $n . '. ' . $move ) );
				
				// Add 65px to line height for next line of text
				$l = $l + $l_height;
				
				// Add one to move number
				$n = $n + 1;
			}
			imagejpeg( $dst_image, $file, 35 );
		// }
		return $file;
	}
	
	function get_log_messages( $log ) {
		
		if( !empty( $log ) ) {
			echo '<ul class="msg-log">';
			
			foreach( $log as $message ) {
				echo '<li>' . $message . '</li>';
			}
			
			echo '</ul>';
		}
		
	}
	
	function get_image_url( $filename ) {
		
		$http_protocol = ( ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) ? "https://" : "http://";
					 
		$image_url = $http_protocol . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . $filename;
						
		return $image_url;
		
	}
	
	// Get default template
	$template_option = get_default_template();
	
	// Get default moves
	$default_moves = get_default_moves();
	
	// Check for user inputs
	if( isset( $_POST['form-submit'] ) ) {
		$submit_ok = true;
		
		// Set user moves
		if( validate_moves( $_POST['move_1'], $_POST['move_2'], $_POST['move_3'] ) == true ) {
			$user_moves = array(
				$_POST['move_1'],
				$_POST['move_2'],
				$_POST['move_3']
			);
		} else {
			$submit_ok = false;
		}
		
		if( $template_option == 'grey' ) {
			// Set user image
			if( validate_image( $_FILES['usr_img'] ) == true ) {
				$user_image = $_FILES['usr_img']['tmp_name'];
			} else {
				$submit_ok = false;
			}
		}
		
		// Check for errors, else create image
		if( $submit_ok == true ) {
			$filename = create_image( $template_option, $user_moves, $user_image );
		} 
	}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, user-scalable=0, maximum-scale=1, initial-scale=1">
		<title>SMRT MVS</title>

		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
		
		<!-- Web fonts -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
		<script src="//use.edgefonts.net/geo.js"></script>
		
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
		<div class="container">
			
			<?php if( isset( $_POST['form-submit'] ) && $submit_ok == true ) { ?>
			
			<div id="smrt-mvs-results" class="row">
				<div class="col-md-12">
					<img src="<?php echo $filename; ?>" class="img-responsive" alt="..."/>
					
					<?php echo get_log_messages( $image_log ); ?>
												
					<?php echo get_log_messages( $moves_log ); ?>
					
					<div class="input-group">
						<input value="<?php echo get_image_url( $filename ); ?>" type="text" id="image-url" class="form-control input-lg" readonly>
						<span class="input-group-btn">
							<button type="button" data-clipboard-target="#image-url" class="btn btn-lg btn-yellow">Copy URL</button>
						</span>
					</div>
					<a title="Back to SMRT MVS" role="button" class="btn btn-lg btn-yellow" href="/smrt_mvs/">Back to you, Mike</a>
				</div>
			</div>
			
			<?php } else { ?>
			
			<div id="smrt-mvs-tab" class="row">
				<div class="col-md-12">
					<form enctype="application/x-www-form-urlencoded" method="get">
						<div class="btn-group btn-group-sm" role="group">
							<button value="green" type="submit" title="Text only" name="t" class="btn btn-yellow<?php if( $template_option == 'green' ) { echo ' active'; } ?>">Green</button>
							<button value="grey" type="submit" title="Text with image" name="t" class="btn btn-yellow<?php if( $template_option == 'grey' ) { echo ' active'; } ?>">Grey</button>
						</div>
					</form>
				</div>
			</div>
			
			<?php switch ( $template_option ) { case 'grey': $dst_image = 'img/sm_grey.png'; break; case 'green': $dst_image = 'img/sm_green.png'; break; } ?>
			
			<div id="smrt-mvs-form" class="row">
				<div class="col-md-12">
					<form enctype="multipart/form-data" method="post">
						<img src="<?php echo $dst_image; ?>" class="img-responsive" alt="Moves template image"/>
						<!-- <img src="img/mvs-splat.svg" class="img-splat" alt="Splat image"/> -->
						
						<?php echo get_log_messages( $image_log ); ?>
												
						<?php echo get_log_messages( $moves_log ); ?>
						
						<?php if( $template_option == 'grey' ) { ?>
						
						<input type="file" name="usr_img" id="usr-img"/>
						<label for="usr-img" class="btn btn-lg btn-default">
							<div class="img-inner">
								<i class="fa fa-picture-o" aria-hidden="true"></i>
								<span>Choose an image...</span>
							</div>
						</label>
						
						<?php } ?>
						
						<div class="mvs-outer template-<?php echo $template_option; ?>">
							<div class="form-group">
								<div class="input-group input-group-lg">
									<div class="input-group-addon">1.</div>
									<input <?php if( isset( $_POST['move_1'] ) ) { echo 'value="' . $_POST['move_1'] . '"'; } ?> type="text" placeholder="<?php echo $default_moves[0]; ?>" name="move_1" maxlength="20" class="form-control" autocomplete="off">
								</div>
							</div>
							<div class="form-group">
								<div class="input-group input-group-lg">
									<div class="input-group-addon">2.</div>
									<input <?php if( isset( $_POST['move_2'] ) ) { echo 'value="' . $_POST['move_2'] . '"'; } ?> type="text" placeholder="<?php echo $default_moves[1]; ?>" name="move_2" maxlength="20" class="form-control" autocomplete="off">
								</div>
							</div>
							<div class="form-group">
								<div class="input-group input-group-lg">
									<div class="input-group-addon">3.</div>
									<input <?php if( isset( $_POST['move_3'] ) ) { echo 'value="' . $_POST['move_3'] . '"'; } ?> type="text" placeholder="<?php echo $default_moves[2]; ?>" name="move_3" maxlength="20" class="form-control" autocomplete="off">
								</div>
							</div>
						</div>
						<input value="Take it to Mo!" type="submit" title="Create your image" name="form-submit" class="btn btn-lg btn-yellow"/>
					</form>
				</div>
			</div>
			
			<?php } ?>
				
		</div>

		<!-- jQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		
		<!-- Bootstrap -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
		
		<!-- Clipboard -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>
		
		<!-- Custom file input -->
		<script src="js/jquery.custom-file-input.js"></script>
		
		<script>
			new Clipboard('.btn');
		</script>
	</body>
</html>