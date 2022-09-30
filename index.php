<?php
require_once 'scripts/__services.php';
require_once 'scripts/__statistics.php';
require_once 'scripts/__testimonials.php';
require_once 'scripts/__news.php';

$now = new DateTime();
$date = new DateTime('12-05-2007');

$yearsInService = $date->diff($now);

$Services = new Services;
$conn = $Services->conn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once 'components/head.php' ?>
	<link rel="stylesheet" href="/css/index.css">
	<script src="js/jquery.visible.min.js" defer></script>
	<title>EPSS | Home</title>
</head>

<body class="body-font">
	<?php include_once 'components/navbar.php' ?>

	<div id="homeCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<div role="banner" class="banner bg-img overlay overlay-black position-relative" style="background-image: url(files/received/1.jpg)" id="bann">
					<div class="container">
						<div class="position-absolute banner-block v-centered">
							<h1 class="font-weight-light heading mb-4 text-white banner-heading" id="bannHd">We offer expert security solutions that work</h1>
							<p class="lead mb-4 text-light">Our duty to our clients is to serve and protect.</p>

							<div>
								<a href="about.php" class="btn btn-warning btn-hover-primary ease px-4 py-3 rounded-0">Learn More</a>
								<a href="contact.php" class="btn btn-outline-light ml-3 px-4 py-3 rounded-0">Contact</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<a class="carousel-control-prev" href="#homeCarousel" data-slide="prev" style="opacity: 0; width:5%">
			<span class="carousel-control-prev-icon"></span>
		</a>
		<a class="carousel-control-next" href="#homeCarousel" data-slide="next" style="opacity: 0; width:5%">
			<span class="carousel-control-next-icon"></span>
		</a>

	</div>

	<section id="about">
		<div class="container-fluid">
			<div class="row flex-md-row-reverse">
				<div class="col-lg-8 p-sm-5 py-4">
					<div class="pl-4 pr-5 pt-3 pt-lg-5">
						<h1 class="font-sm h6 heading mb-3 sub-title d-flex align-items-center text-primary text-uppercase">Who Are We</h1>

						<div class="font-weight-normal h3 heading mb-5">Focused on the all-round protection of what matters to you</div>

						<div>
							<p>EPSS Security is a Nigerian-based private security company, dedicated to providing on-site security guard services and mobile patrol services for commercial and residential properties. Our thoroughly vetted and trained security officers work as armed or unarmed guards, uniformed and plain clothed, on foot, or vehicle patrol.</p>
							<p>We deliver quick and efficient services you need, aimed at fulfilling the unique needs of your business and protecting your people and property. We provide value to you by understanding your requirements and providing receptive security servcies at national level with the benefit of our comprehensive support network.</p>
							<a href="about.php" class="btn btn-primary btn-hover-warning ease px-4 py-3 rounded-0">Read More</a>
						</div>
					</div>
				</div>

				<div class="col-lg-4 bg-dark-blue p-5">
					<ul aria-label="Features" class="list-unstyled mb-0" id="featuresList">
						<li class="ease p-lg-4 p-5 position-relative z-1">
							<div class="heading h5 text-white">Licensed & Approved</div>
							<div class="d-flex">
								<p class="mb-0 text-white-50">We are government-approved and our staff are licensed and trained</p>
								<i class="fa-solid fa-award ease"></i>
							</div>
						</li>

						<li class="ease p-lg-4 p-5 position-relative z-1">
							<div class="heading h5 text-white">Professional</div>
							<div class="d-flex">
								<p class="mb-0 text-white-50">Our staff are trained to deliver efficiently, respectfully and on time</p>
								<i class="fa-regular fa-handshake ease"></i>
							</div>
						</li>

						<li class="ease p-lg-4 p-5 position-relative z-1">
							<div class="heading h5 text-white">Cost-Effective</div>
							<div class="d-flex">
								<p class="mb-0 text-white-50">We protect what you entrust to us without draining your finances</p>
								<i class="fa-solid fa-piggy-bank ease"></i>
							</div>
						</li>

						<li class="ease p-lg-4 p-5 position-relative z-1">
							<div class="heading h5 text-white">Customer Support</div>
							<div class="d-flex">
								<p class="mb-0 text-white-50">We handle your complaints and questions to see to your satisfaction</p>
								<i class="fa-solid fa-headset ease"></i>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<section id="services" class="bg-light py-5">
		<div class="container pb-5">
			<h1 class="font-sm h6 heading mb-5 sub-title d-flex align-items-center text-black text-uppercase">Our Services</h1>

			<?php
			$Services->getExcerptCards($conn);
			?>
		</div>
	</section>

	<section id="stats">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-10 col-md-11 col-12 mt-n5">
					<?php
					$Statistics = new Statistics;
					$stats = $Statistics->get($conn);
					?>
					<div class="row bg-primary text-center py-3">
						<div class="col-md-3">
							<div class="d-flex flex-column justify-content-center align-items-center p-5">
								<div class="h1 heading font-weight-bold text-white" data-stat-count="<?= $yearsInService->format("%y") ?>">0</div>
								<p class="mb-0 text-light">Years of Experience</p>
							</div>
						</div>
						<div class="col-md-3">
							<div class="d-flex flex-column justify-content-center align-items-center p-5">
								<div class="h1 heading font-weight-bold text-white" data-stat-count="<?= $stats['offices'] ?>">0</div>
								<p class="mb-0 text-light">National Offices</p>
							</div>
						</div>
						<div class="col-md-3">
							<div class="d-flex flex-column justify-content-center align-items-center p-5">
								<div class="h1 heading font-weight-bold text-white" data-stat-count="<?= $stats['clients'] ?>" data-stat-estimate="<?= $stats['estimate_clients'] == 1 ? 'true' : 'false' ?>">0</div>
								<p class="mb-0 text-light">Client firms</p>
							</div>
						</div>
						<div class="col-md-3">
							<div class="d-flex flex-column justify-content-center align-items-center p-5">
								<div class="h1 heading font-weight-bold text-white" data-stat-count="<?= $stats['guards'] ?>" data-stat-estimate="<?= $stats['estimate_guards'] == 1 ? 'true' : 'false' ?>">0</div>
								<p class="mb-0 text-light">Guards</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section id="testimonials" class="py-5">
		<div class="container py-4">
			<div class="row">
				<div class="col-lg-5">
					<div class="">
						<h1 class="font-sm h6 heading mb-3 sub-title d-flex align-items-center text-warning text-uppercase">Testimonials</h1>

						<div class="font-weight-normal h3 heading mb-4">Our clients are satisfied with what we do</div>

						<p class="lead">To ensure you are happy with what we do, we employ solutions that are tailored to meet your needs and execute them efficiently.</p>
					</div>
				</div>

				<div class="col-lg-6 offset-lg-1 mt-3 mt-lg-0">
					<?php
					$Testimonials = new Testimonials;
					$Testimonials->getSlides($conn);
					?>
				</div>
			</div>
	</section>

	<section id="news" class="py-4">
		<h1 class="font-sm h6 heading mb-5 sub-title d-flex align-items-center text-primary text-uppercase justify-content-center">News</h1>
		<div class="container-fluid">
			<div class="row">
				<?php
				$News = new News;
				$News->getCards($conn);
				?>
			</div>
			<div class="mt-4 text-right">
				<a href="news.php" class="btn btn-dark px-4 py-3 rounded-0">All News<i class="fa-solid fa-arrow-right ml-2"></i></a>
			</div>
		</div>
	</section>

	<?php include_once 'components/footer.php' ?>
</body>

<script>
	$(document).ready(function() {
		$('#mnNav .nav-link[href="index.php"]').addClass('active');

		var scrollTimeout = null;
		const windowHeight = window.innerHeight;
		const scrollHeigt = $('body').height();

		$(window).scroll(function() {
			clearTimeout(scrollTimeout);

			scrollTimeout = setTimeout(function() {
				updateStats();
			}, 50);
		});

		updateStats();

		function updateStats() {
			if (isInViewport(document.getElementById('stats'))) {
				$('[data-stat-count]:not([data-stat-loaded])').each(function() {
					var $this = $(this);
					var to = parseInt($this.attr('data-stat-count'));
					var estimate = $this.attr('data-stat-estimate') === 'true';

					if(!to) return;

					var count = 0;

					var statInterval = null;

					if (to < 10) {
						interval = 100;
					} else if (to < 100) {
						interval = 50;
					} else {
						interval = 5;
					}

					var updateStatCount = () => {
						$this.html(estimate ? `${count}<sup>+</sup>` : count);
						if (count == to) clearInterval(statInterval);
						++count;
					};

					statInterval = setInterval(updateStatCount, interval);

					$this.attr('data-stat-loaded', 'true');
					$(window).off('scroll');
				});
			}
		}
	})
</script>

</html>