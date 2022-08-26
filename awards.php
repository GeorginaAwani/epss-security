<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once 'components/head.php' ?>
	<title>EPSS | Awards</title>

	<style>
		.award-description {
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
	</style>
</head>

<body class="body-font">
	<?php include_once 'components/navbar.php' ?>

	<div role="banner" class="banner bg-img overlay overlay-black position-relative bg-position-center" style="background-image: url(files/received/14.jpg)" id="bann">
		<div class="container">
			<div class="position-absolute banner-block v-centered">
				<h1 class="font-sm h6 heading mb-3 sub-title d-flex align-items-center text-warning text-uppercase">Awards</h1>
				<h2 class="font-weight-light heading mb-4 text-white banner-heading" id="bannHd">Our expertise is acknowledged</h2>
			</div>
		</div>
	</div>

	<main class="container py-5 gallery" id="awardMain"></main>

	<div id="awardModal" class="modal fade image-modal" style="background-color: #000;" data-backdrop="static">
		<button type="button" id="awardModalClose" class="btn d-flex justify-content-center align-items-center h2 position-absolute text-white m-md-4 mx-3 my-2 font-weight-light" data-dismiss="modal">&times;</button>
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="bg-transparent modal-content rounded-0">
				<div class="modal-body p-0">
					<div id="awardCarousel" class="carousel slide" data-interval="false">
						<div class="carousel-indicators position-relative mt-4 mb-md-4 mb-2"></div>
						<div class="d-flex justify-content-between mb-3 mb-md-0">
							<a class="carousel-control-prev btn" href="#awardCarousel" data-slide="prev"><i class="fa-solid fa-chevron-left"></i></a>
							<a class="carousel-control-next btn" href="#awardCarousel" data-slide="next"><i class="fa-solid fa-chevron-right"></i></a>
						</div>
						<div class="carousel-inner"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php include_once 'components/footer.php' ?>
</body>

<script>
	$(document).ready(function() {
		$('#mnNavCllps [href="awards.php"]').addClass('active');

		const _awards = '/scripts/_awards.php';

		function loadAward(i = null) {
			var send = {
				a: 'card'
			};
			if (!!i) send.l = i;

			loadImageGallery(send, _awards);
		}

		$('#awardMain').html(loader);

		loadAward();

		$('#awardMain').on('click', '#awardLoadBtn', function(){
			var i = $(this).attr('data-load-id');
			if(!i) return;

			$(this).parent().replaceWith(loader);
			loadAward(i);
		})
	});
</script>

</html>