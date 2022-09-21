<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once 'components/head.php' ?>
	<title>EPSS | Gallery</title>
	<link rel="stylesheet" href="/css/gallery.css?<?= time() ?>">
</head>

<body class="body-font">
	<?php include_once 'components/navbar.php' ?>

	<div role="banner" class="banner bg-img overlay overlay-black position-relative" style="background-image: url(files/received/26-1.jpg)" id="bann">
		<div class="container">
			<div class="position-absolute banner-block v-centered">
				<h1 class="font-sm h6 heading mb-3 sub-title d-flex align-items-center text-warning text-uppercase">Gallery</h1>
			</div>
		</div>
	</div>

	<main class="container-fluid gallery" id="gallery" style="background-color: #000;"></main>

	<div id="galleryModal" class="modal fade image-modal" style="background-color: #000;" data-backdrop="static">
		<button type="button" id="galleryModalClose" class="btn d-flex justify-content-center align-items-center h2 position-absolute text-white m-md-4 mx-3 my-2 font-weight-light" data-dismiss="modal">&times;</button>
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="bg-transparent modal-content rounded-0">
				<div class="modal-body p-0">
					<div id="galleryCarousel" class="carousel slide" data-interval="false">
						<div class="carousel-indicators position-relative mt-4 mb-md-4 mb-2"></div>
						<div class="d-flex justify-content-between mb-3 mb-md-0">
							<a class="carousel-control-prev btn" href="#galleryCarousel" data-slide="prev"><i class="fa-solid fa-chevron-left"></i></a>
							<a class="carousel-control-next btn" href="#galleryCarousel" data-slide="next"><i class="fa-solid fa-chevron-right"></i></a>
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
		$('#mnNavCllps [href="gallery.php"]').addClass('active');

		const _gallery = '/scripts/_gallery.php';

		function loadAward(i = null) {
			var send = {
				a: 'card'
			};
			if (!!i) send.l = i;

			loadImageGallery(send, _gallery);
		}

		$('#gallery').html(loader);

		loadAward();

		$('#gallery').on('click', '#galleryLoadBtn', function(){
			var i = $(this).attr('data-load-id');
			if(!i) return;

			$(this).parent().replaceWith(loader);
			loadAward(i);
		})
	})
</script>

</html>