<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once 'components/head.php' ?>
	<title>EPSS | Services</title>
	<link rel="stylesheet" href="css/services.css?<?= time() ?>">
</head>

<body class="body-font">
	<?php include_once 'components/navbar.php' ?>

	<div role="banner" class="banner bg-img overlay overlay-black position-relative" style="background-image: url(files/received/8.jpg)" id="bann">
		<div class="container">
			<div class="position-absolute banner-block v-centered">
				<h1 class="font-sm h6 heading mb-3 sub-title d-flex align-items-center text-warning text-uppercase">Services</h1>
				<h2 class="font-weight-light heading mb-4 text-white banner-heading" id="bannHd">We have an extensive range of options you can choose from</h2>
			</div>
		</div>
	</div>

	<section id="main" style="background-color: #f7f9fb;">
		<div class="container-fluid pl-sm-5 pr-sm-4 py-4 py-sm-5">
			<div class="font-weight-normal h3 heading mb-5">Comprehensive protection alternatives for people, places and events</div>

			<div class="mt-3 mt-sm-5 pb-3 pb-sm-0" id="servicesList"></div>
		</div>
	</section>

	<?php include_once 'components/footer.php'; ?>
</body>

<script>
	$(document).ready(function() {
		$('#mnNav .nav-link[href="services.php"]').addClass('active');

		$.post('scripts/_services.php', {a: 'list'}, function(d, s){
			$('#servicesList').html(d);

			if(location.hash){
				$(location.hash)[0].scrollIntoView();
			}
		})
	})
</script>

</html>