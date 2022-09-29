<?php
require_once 'scripts/__team.php';
require_once 'scripts/__clients.php';

$Team = new Team;
$conn = $Team->conn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once 'components/head.php' ?>
	<title>EPSS | About</title>
	<link rel="stylesheet" href="css/about.css">
</head>

<body class="body-font">
	<?php include_once 'components/navbar.php' ?>

	<div role="banner" class="banner bg-img overlay overlay-black position-relative" style="background-image: url(files/received/9.jpg)" id="bann">
		<div class="container">
			<div class="position-absolute banner-block v-centered">
				<h1 class="font-sm h6 heading mb-3 sub-title d-flex align-items-center text-warning text-uppercase">About</h1>
				<h2 class="font-weight-light heading mb-4 text-white banner-heading" id="bannHd">We believe in low-profile, risk-based and threat-driven solutions.</h2>
			</div>
		</div>
	</div>

	<section id="introduction">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-8 pb-4 pb-md-0">
					<div class="p-md-5 px-3 py-4">
						<h1 class="font-sm h6 heading mb-3 sub-title d-flex align-items-center text-primary text-uppercase">Who we are</h1>
						<div class="font-weight-normal h3 heading mb-5">A security agency you can trust</div>

						<p>EPSS Security is a leading security and facility services company. We provide proactive security services and cutting-edge smart technology to deliver evolving, tailored solutions that allow our clients to focus on their core business. Our excellence starts with our local leadership and local presence.</p>

						<p>Operating under Injaz Limited, across all 36 states in Nigeria, our experienced team helps deliver our promise nationwide: protecting people and things that matter to you.

						<div class="heading h5 text-black">Mission Statement</div>
						<p class="font-italic">To plan, develop and facilitate innovative security management procedures, fire and safety preparedness.</p>

						<div class="heading h5 text-black">Vision Statement</div>
						<ul class="font-italic pl-4">
							<li>To constantly provide innovative and comprehensive security solutions.</li>
							<li>To operate within a strict moral, ethical and legal code.</li>
							<li>To help develop effective, optimal operational methodologies that increase daily levels of security in our environs.</li>
							<li>A strong drve to ensure quality, professionalism and integrity per excellence.</li>
						</ul>
					</div>
				</div>

				<div class="col-md-4 mt-n5 mb-n5">
					<div class="bg-img h-100 overlay position-relative bg-fixed" id="introImg" style="background-image: url(files/received/11-1.jpg);"></div>
				</div>
			</div>
		</div>
	</section>

	<section id="process" class="py-5 bg-dark-blue">
		<div class="container text-center text-white position-relative z-1">
			<h1 class="font-sm h6 heading mb-3 sub-title d-flex align-items-center text-warning text-uppercase justify-content-center mt-4 mt-sm-0">How we work</h1>
			<div class="font-weight-normal h3 heading mb-4">The process that gets the job done</div>

			<ol class="font-4 mx-auto text-left w-75 mb-0 pl-5 pl-sm-0">
				<li class="mb-3 pl-3">
					<div class="body-font">
						<p class="heading h5">Asset evaluation</p>
						<div>We identify properties and people to be protected, and draw information including data on our client's operations, plans and strategies. We also determine the security level of all assests under our care. Our team of Security Mitigators conduct a Risk Assessment to ascertain the vulnerability of the location, then put together a documentation on the risk analysis and recommendations.</div>
					</div>
				</li>

				<li class="mb-3 pl-3">
					<div class="body-font">
						<p class="heading h5">Deployment of Operatives</p>
						<div>After the Risk Assessment and Analysis are reviewed and agreed by both the Principal (owner of the location) and the Vendor (Security Company), the guards will be deployed to perform their duties.</div>
					</div>
				</li>

				<li class="mb-3 pl-3">
					<div class="body-font">
						<p class="heading h5">Security practices</p>
						<div>We draft and plan asset clarification practices, risk assessment and acceptance, asset ownership, asset handling responsibilities, policies regarding mishandling assests, security violations and how they are handled and security audits. The operatives are monitored by our team of supervisors and Operation Managers through physical visition or phone/video calls. Operative training is done either onsite or remotely. Operatives are then equipped with all necessary security apparatus.</div>
					</div>
				</li>

				<li class="pl-3">
					<div class="body-font">
						<p class="heading h5">Evaluation</p>
						<div>For optimal delivery, we reassess security management in case of rampant security violations, structural change in the client organisation, change in environment, change in staff or gadgets used or change in budget. Our Customer Care department remains in touch with the client, and the Guards Feedback Form is sent out to the guards to enquire of their welfare.</div>
					</div>
				</li>
			</ol>

			<i class="fas fa-tasks shadow-icon text-white position-absolute z-n1" id="introducton2Icon"></i>
		</div>
	</section>

	<section id="clients" class="py-4 py-sm-5">
		<div class="container">
			<h1 class="font-sm h6 heading mb-3 sub-title d-flex align-items-center text-primary text-uppercase">Our Reputable Clients</h1>
			<div class="font-weight-normal h3 heading mb-4">We promised and delivered</div>
			<p>EPSS Private Security Company is trusted by dozens of businesses in Nigeria.</p>

			<div class="pt-4 pb-4">
				<?php
					$Clients = new Clients;
					$Clients->getCards($conn);
				?>
			</div>
		</div>
	</section>

	<div class="banner bg-img bg-position-bottom overlay overlay-black position-relative" id="divider" style="height: 10rem;"></div>

	<section id="management" class="py-4 py-sm-5 position-relative">
		<div class="container-fluid">
			<div class="text-center">
				<h1 class="font-sm h6 heading mb-3 sub-title d-flex align-items-center text-primary text-uppercase justify-content-center">Our team</h1>
				<div class="font-weight-normal h3 heading mb-4">The faces behind our success</div>
			</div>

			<div class="row pt-sm-4">
				<?php $Team->getFullList($conn); ?>
			</div>
		</div>
	</section>

	<?php include_once 'components/footer.php' ?>
</body>

<script>
	$(document).ready(function() {
		$('#mnNav .nav-link[href="about.php"]').addClass('active');
	})
</script>

</html>