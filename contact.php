<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once 'components/head.php' ?>
	<title>EPSS | Contact</title>
	<link rel="stylesheet" href="/css/contact.css?<?= time() ?>">
</head>

<body class="body-font">
	<?php include_once 'components/navbar.php' ?>

	<div role="banner" class="banner bg-img overlay overlay-black position-relative" style="background-image: url(files/received/26-1.jpg)" id="bann">
		<div class="container">
			<div class="position-absolute banner-block v-centered">
				<h1 class="font-sm h6 heading mb-3 sub-title d-flex align-items-center text-warning text-uppercase">Contact</h1>
				<h2 class="font-weight-light heading mb-4 text-white banner-heading" id="bannHd">Round-the-clock support</h2>
			</div>
		</div>
	</div>

	<section id="contact" class="py-4 py-sm-5">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-5" id="contactDetails">
					<ul class="list-unstyled">
						<li>
							<ul id="footerSocials" class="pl-4 list-inline h5 mb-0">
								<li class="list-inline-item"><a href="https://web.facebook.com/EPSS-Private-Security-Services-LTD-1393945044177997/" class="ease text-reset" aria-label="EPSS on Facebook"><i class="fab fa-facebook-f"></i></a></li>
								<li class="list-inline-item"><a href="http://www.twitter.com/EpssSecurity" class="ease text-reset" aria-label="EPSS on Twitter" title="Twitter"><i class="fab fa-twitter-square"></i></a></li>
								<li class="list-inline-item"><a href="https://www.instagram.com/epsssecurity" class="ease text-reset" aria-label="EPSS on Instagram" title="Instagram"><i class="fab fa-instagram"></i></a></li>
							</ul>
						</li>

						<li>
							<div class="d-flex align-items-center">
								<div class="mr-3"><i class="fa-solid fa-phone" style="color: var(--orange)"></i></div>
								<div>
									<div class="text-uppercase text-muted small heading">Phone</div>
									<p class="mb-0">
										<a href="tel:+2348060256803" class="text-reset">+234 806 025 6803</a>
										<a href="tel:+2349062541550" class="text-reset">+234 906 254 1550</a>
									</p>
								</div>
							</div>
						</li>

						<li>
							<div class="d-flex align-items-center">
								<div class="mr-3"><i class="fa-regular fa-envelope" style="color: var(--red)"></i></div>
								<div>
									<div class="text-uppercase text-muted small heading">Email</div>
									<p class="mb-0"><a href="mailto:admin@epsssecurity.com" class="text-reset">admin@epsssecurity.com</a></p>
								</div>
							</div>
						</li>

						<li>
							<div class="d-flex align-items-center">
								<div class="mr-3"><i class="fa-solid fa-location-dot" style="color: var(--pink)"></i></div>
								<div>
									<div class="text-uppercase text-muted small heading">Office</div>
									<address class="mb-1"><b>Port Harcourt:</b> Plot 80B, Peter Odili Road, Trans-Amadi Industrial Layout</address>
									<address class="mb-0"><b>Lagos:</b> 43B Churchgate street, Victoria Island</address>
								</div>
							</div>
						</li>

						<li>
							<div class="d-flex align-items-center">
								<div class="mr-3"><i class="fa-regular fa-calendar-days" style="color: var(--purple)"></i></div>
								<div>
									<div class="text-uppercase text-muted small heading">Open Days</div>
									<p class="mb-0">Monday - Sunday</p>
								</div>
							</div>
						</li>

						<li>
							<div class="d-flex align-items-center">
								<div class="mr-3"><i class="fa-regular fa-clock" style="color: var(--indigo)"></i></div>
								<div>
									<div class="text-uppercase text-muted small heading">Visiting Hours</div>
									<p class="mb-0">8am - 5pm</p>
								</div>
							</div>
						</li>
					</ul>
				</div>

				<div class="col-md-7 mt-md-0 mt-3">
					<form action="" id="contactForm">
						<div class="form-group pb-3">
							<input type="text" class="form-control form-control-lg px-md-4 px-3 py-3 rounded-0" id="name" name="n" placeholder="Name" aria-label="Name" required>
						</div>

						<div class="form-group pb-3">
							<input type="email" class="form-control form-control-lg px-md-4 px-3 py-3 rounded-0" id="email" name="e" placeholder="Email" aria-label="Your email address" required>
						</div>

						<div class="form-group pb-3">
							<input type="tel" class="form-control form-control-lg px-md-4 px-3 py-3 rounded-0" id="phone" name="p" placeholder="Phone number" aria-label="Your phone number" required>
						</div>

						<div class="form-group pb-3">
							<input type="text" class="form-control form-control-lg px-md-4 px-3 py-3 rounded-0" id="subject" name="s" placeholder="Reason for inquiry" aria-label="Reason for inquiry" required>
						</div>

						<div class="form-group pb-3">
							<textarea name="m" id="message" rows="4" style="resize: none;" placeholder="Message" aria-label="Message" class="form-control form-control-lg px-md-4 px-3 py-3 rounded-0" required></textarea>
						</div>

						<div id="contactMessage"></div>

						<div class="form-group mb-0 pt-md-3">
							<button type="submit" class="btn btn-primary ease px-4 py-3 rounded-0">Submit Message</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<?php include_once 'components/footer.php' ?>
</body>

<script>
	$(document).ready(function() {
		$('#mnNav .nav-link[href="contact.php"]').addClass('active');

		function formError() {
			removeAlertOnInput();
			$('#contactMessage').html(`<div class="alert alert-danger font-4 rounded-0" role="alert">Couldn't submit your response. Try again later.</div>`);
		}

		function removeAlertOnInput() {
			$('#contactForm .form-control').on('input', function() {
				$('#contactMessage').empty();
				$('#contactForm .form-control').off('input');
			})
		}

		$('#contactForm').submit(function(e) {
			e.preventDefault();

			var s = $(this).find(':submit');

			if (s.is('[disabled]')) return;

			try {
				s.prepend('<i class="fa-solid fa-spinner mr-2 fa-spin"></i>');
				s.attr('disabled', 'disabled');

				var o = {a: 'set'};
				$('#contactForm .form-control').each(function() {
					if (!this.value) throw new Error('required field is not provided');

					o[this.name] = this.value;
				});

				$.post('/scripts/_contacts.php', o, function(d, s) {
					try {
						if (s !== 'success') throw new Error('Ajax failed; status: ' + s);

						if (d !== 'true') throw new Error('contact failed; data: ' + d);

						removeAlertOnInput();
						$('#contactMessage').html(`<div class="alert alert-success font-4 rounded-0" role="alert">We've submitted your message. We'll write you back shortly.</div>`);

						$('#contactForm').trigger('reset');
					} catch (error) {
						console.error(error);
						formError();
					} finally {
						$('#contactForm :submit').removeAttr('disabled').text('Submit Message');
					}
				})
			} catch (error) {
				console.log(error);
				s.removeAttr('disabled').text('Submit Message');
				formError();
			}
		});
	})
</script>

</html>