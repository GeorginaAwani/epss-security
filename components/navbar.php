<link rel="stylesheet" href="css/nav.css?<?= time() ?>">
<header id="hd" class="sticky-top">
	<nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm" id="mnNav">
		<div class="container-fluid">
			<a class="navbar-brand d-flex align-items-center p-0" href="index.php">
				<!-- <img src="logos/EPSS_sm-light.png" alt="EPSS Logo" id="nvLg"> -->
				<img src="logos/logo.jpg" alt="EPSS Logo" id="nvLg">
			</a>

			<button class="border-0 navbar-toggler" type="button" data-toggle="collapse" data-target="#mnNavCllps">
				<div class="d-flex flex-column">
					<span class="navbar-bar"></span>
					<span class="navbar-bar"></span>
					<span class="navbar-bar"></span>
				</div>
			</button>

			<div id="mnNavCllps" class="collapse navbar-collapse">
				<ul class="navbar-nav heading">
					<li class="nav-item mx-4">
						<a class="nav-link position-relative ease px-0" href="index.php">Home</a>
					</li>

					<li class="nav-item mx-4">
						<a class="nav-link position-relative ease px-0" href="about.php">About</a>
					</li>

					<li class="nav-item mx-4">
						<a class="nav-link position-relative ease px-0" href="services.php">Services</a>
					</li>

					<li class="nav-item mx-4">
						<a class="nav-link position-relative ease px-0" href="news.php">News</a>
					</li>

					<li class="nav-item mx-4">
						<a class="nav-link position-relative ease px-0" href="awards.php">Awards</a>
					</li>

					<li class="nav-item mx-4">
						<a class="nav-link position-relative ease px-0" href="gallery.php">Gallery</a>
					</li>

					<li class="nav-item mx-4">
						<a class="nav-link position-relative ease px-0" href="contact.php">Contact</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<div id="secNav" class="p-1 font-4 d-none d-lg-block bg-dark-blue">
		<div class="container">
			<div class="d-flex justify-content-between">
				<ul id="navContact" class="list-inline mb-0 font-sm">
					<li class="list-inline-item mr-3 text-white">
						<i class="fas fa-phone-alt mr-1 text-warning"></i>
						<a href="tel:+2348060256803" class="text-white">(+234) 806 025 6803</a>
						<span class="mx-1">,</span>
						<a href="tel:+2349062541550" class="text-white">(+234) 906 254 1550</a>
					</li>
					<li class="list-inline-item mr-3">
						<i class="fas fa-envelope mr-1 text-warning"></i>
						<a href="mailto:admin@epsssecurity.com" class="text-white">admin@epsssecurity.com</a>
					</li>
					<li class="list-inline-item mr-3 text-warning">
						<i class="fas fa-map-marker-alt mr-1"></i>
						<a href="https://goo.gl/maps/XododdL2pWfTbjNv8" target="_blank" class="text-white">43B Churchgate street, VI, Lagos</a>
					</li>
				</ul>

				<ul id="navSocial" class="list-inline mb-0 h5">
					<li class="list-inline-item"><a href="https://web.facebook.com/EPSS-Private-Security-Services-LTD-1393945044177997/" class="ease text-light font-sm" aria-label="EPSS on Facebook"><i class="fab fa-facebook-f"></i></a></li>
					<li class="list-inline-item"><a href="http://www.twitter.com/EpssSecurity" class="ease text-light font-sm" aria-label="EPSS on Twitter" title="Twitter"><i class="fab fa-twitter-square"></i></a></li>
					<li class="list-inline-item"><a href="https://www.instagram.com/epsssecurity" class="ease text-light font-sm" aria-label="EPSS on Instagram" title="Instagram"><i class="fab fa-instagram"></i></a></li>
				</ul>
			</div>
		</div>
	</div>
</header>