<aside class="bg-dark-blue col-lg-3 py-lg-4 py-2 px-0" id="sideNav">
	<div class="align-items-center d-flex justify-content-between justify-content-lg-center px-4 align-items-center">
		<div id="sideNavBrand" class="heading h4 text-white mb-0">EPSS</div>
		<button class="btn bg-transparent text-white d-lg-none" id="sideNavOpen" aria-controls="sideNavNav"><i class="fa-solid fa-bars"></i><span class="sr-only">Open Navigation</span></button>
		
	</div>

	<nav class="mt-0 mt-lg-4 navbar navbar-dark px-0" id="sideNavNav">
		<div class="d-lg-none px-4 align-items-center d-flex justify-content-between w-100">
			<div id="sideNavBrand2" class="heading h4 text-white mb-0">EPSS</div>
			<button class="btn bg-transparent text-warning" id="sideNavClose" aria-controls="sideNavNav"><i class="fa-solid fa-xmark"></i><span class="sr-only">Close Navigation</span></button>
		</div>
		<ul class="nav navbar-nav w-100 heading">
			<li class="nav-item" aria-hidden="true">
				<div class="nav-link rounded-0"></div>
			</li>
			<li class="nav-item">
				<a class="nav-link px-4 ease rounded-0" href="index.php" id="dBNav"><i class="fa-solid fa-bars-staggered mr-3"></i></i>Dashboard</a>
				<!-- Team members, counts, services -->
			</li>
			<li class="nav-item">
				<a class="nav-link px-4 ease rounded-0" href="news.php" id="nENav"><i class="fa-regular fa-bookmark mr-3"></i>News</a>
			</li>
			<li class="nav-item">
				<a class="nav-link px-4 ease rounded-0" href="clients.php" id="cNav"><i class="fa-regular fa-user mr-3"></i>Clients</a>
			</li>
			<li class="nav-item">
				<a href="gallery.php" class="nav-link px-4 ease rounded-0" id="glrNav"><i class="fa-regular fa-images mr-3"></i>Gallery</a>
			</li>
			<li class="nav-item">
				<a href="contacts.php" class="nav-link px-4 ease rounded-0" id="ctcNav"><i class="fa-regular fa-envelope mr-3"></i>Contacts</a>
			</li>
			<li class="nav-item">
				<a href="awards.php" class="nav-link px-4 ease rounded-0" id="awNav"><i class="fa-regular fa-star mr-3"></i>Awards</a>
			</li>
			<!-- <li class="nav-item">
				<a href="faqs.php" class="nav-link px-4 ease rounded-0" id="faqNav"><i class="fa-regular fa-circle-question mr-3"></i>FAQs</a>
			</li> -->
			<li class="nav-item" aria-hidden="true">
				<div class="nav-link rounded-0"></div>
			</li>
			<li class="nav-item mt-4" id="sideUser">
				<a href="account.php" class="nav-link px-4 ease rounded-0 btn">
					<div class="d-flex align-items-center">
						<div id="adminUserPfp">
							<?= $Administrator->profilePhoto() ?>
						</div>
						<div class="align-items-center d-flex ml-3 text-left">
							<div>
								<?php @session_start() ?>
								<div class="mb-0 h6 text-white"><?= $_SESSION[Administrator::SESSION_FULLNAME] ?></div>
								<div class="font-sm"><?= $_SESSION[Administrator::SESSION_ROLE] ?></div>
							</div>
							<i class="fa-solid fa-angle-right ml-3"></i>
						</div>
					</div>
				</a>
			</li>
		</ul>
	</nav>

</aside>