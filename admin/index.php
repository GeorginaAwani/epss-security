<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/scripts/functions.php";
$ROOT = ROOT;

$Administrator = new Administrator;
if (!$Administrator->isLoggedIn()) header('Location: login.php');
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
	<?php include_once "{$ROOT}/components/head.php" ?>
	<title>EPSS -- Admin | Dashboard</title>
	<link rel="stylesheet" href="/css/admin.css?<?= time() ?>">
	<script src="/js/admin.js?<?= time() ?>"></script>

	<style>
		#tm_Img {
			height: 15rem;
		}

		#service_Img {
			height: 20rem;
		}
	</style>

</head>

<body class="body-font h-100">
	<div class="container-fluid h-100">
		<div class="row h-100">
			<?php require_once "{$ROOT}/components/sidebar.php" ?>
			<div class="col-lg-9">
				<main id="main" class="container px-0 px-lg-2">
					<ul class="border-bottom border-light heading mt-4 nav nav-pills rounded pb-2 pb-lg-0">
						<li class="nav-item">
							<a class="ease nav-link position-relative px-4 rounded" data-toggle="pill" role="tab" href="#stats" id="statsTabToggle">Stats</a>
						</li>
						<li class="nav-item">
							<a class="ease nav-link position-relative px-4 rounded group-tab-toggle" data-toggle="pill" role="tab" href="#team" id="teamTabToggle">Team</a>
						</li>
						<li class="nav-item">
							<a class="ease nav-link position-relative px-4 rounded group-tab-toggle" data-toggle="pill" role="tab" href="#services" id="servicesTabToggle">Services</a>
						</li>
						<li class="nav-item">
							<a class="ease nav-link position-relative px-4 rounded group-tab-toggle" data-toggle="pill" role="tab" href="#testimonials" id="testimonialsTabToggle">Testimonials</a>
						</li>
					</ul>

					<div class="py-4 tab-content">
						<div class="tab-pane" role="tabpanel" id="stats">
							<form action="" id="statsForm">
								<fieldset id="statsFieldset" role="presentation">
									<div class="form-group">
										<label for="on" class="heading font-sm text-muted">Offices in Nigeria</label>
										<input type="number" name="on" id="on" class="ease form-control py-2 px-3" required min="1">
									</div>

									<div class="form-group">
										<label for="cf" class="heading font-sm text-muted">Client firms</label>
										<input type="number" name="cf" id="cf" class="ease form-control py-2 px-3" required min="1">
										<div class="mt-2">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="cfChk" name="cfc">
												<label class="custom-control-label" for="cfChk">Estimate this value</label>
												<p class="font-sm text-muted">Will add a &plus; sign along with this count</p>
											</div>

										</div>
									</div>

									<div class="form-group">
										<label for="gc" class="heading font-sm text-muted">Guards</label>
										<input type="number" name="gc" id="gc" class="ease form-control py-2 px-3" required>
										<div class="mt-2">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="gcChk" name="gcc">
												<label class="custom-control-label" for="gcChk">Estimate this value</label>
												<p class="font-sm text-muted">Will add a &plus; sign along with this count</p>
											</div>

										</div>
									</div>

									<div class="mt-5">
										<button type="submit" class="btn btn-primary px-3 px-4 py-2">Save</button>
									</div>
								</fieldset>
							</form>
						</div>

						<div class="tab-pane group-tab fade" role="tabpanel" id="team">
							<ul class="nav nav-pills" hidden>
								<li class="nav-item">
									<a class="ease nav-link position-relative px-4 rounded main-tab-toggle" data-toggle="pill" role="tab" href="#team_all" id="teamAllToggle">All Team Members</a>
								</li>
								<li class="nav-item">
									<a class="ease nav-link position-relative px-4 rounded edit-tab-toggle" data-toggle="pill" role="tab" href="#team_edit" id="teamEditToggle">Edit Team Members</a>
								</li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane fade main-tab" id="team_all">
									<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
										<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0">All Team Members</h1>
										<button type="button" class="btn btn-primary edit-trigger-btn" id="addTeamMemberBtn"><i class="fa-solid fa-plus mr-1"></i>Add New</button>
									</div>
									<div class="table-responsive">
										<table class="table" id="teamTable">
											<thead class="heading text-muted">
												<tr>
													<th>Member</th>
													<th>Description</th>
													<th></th>
												</tr>
											</thead>

											<tbody></tbody>
										</table>
									</div>
								</div>

								<div class="tab-pane fade edit-tab" id="team_edit">
									<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
										<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0 edit-form-title" id="teamEditTtl" data-new-text="Add Team Member" data-edit-text="Edit Team Member">Add Team Member</h1>
										<button type="button" class="btn btn-light edit-form-cancel-btn" id="cancelTeamMemberBtn"><i class="fa-solid fa-xmark mr-1"></i> Cancel</button>
									</div>
									<form id="teamEditForm" class="edit-form" aria-labelledby="teamEditTll">
										<div class="row pt-4">
											<div class="text-center col-sm-4">
												<div id="tm_Img" class="bg-img bg-light mb-2 edit-form-img mx-auto"></div>
												<label class="btn btn-sm btn-light">Upload image <input type="file" name="i" id="tm_ImgFI" class="sr-only edit-form-upload"></label>
											</div>
											<div class="flex-fill col-sm-8">
												<div class="form-group">
													<label class="heading font-sm text-muted" for="tm_name">Name</label>
													<input type="text" name="n" id="tm_name" class="ease form-control py-2 px-3" maxlength="25" required>
												</div>

												<div class="form-group">
													<label class="heading font-sm text-muted" for="tm_pos">Position</label>
													<input type="text" name="p" id="tm_pos" class="ease form-control py-2 px-3" maxlength="35" required>
												</div>

												<div class="form-group">
													<label class="heading font-sm text-muted" for="tm_desc">Description</label>
													<textarea name="d" id="tm_desc" required="" class="ease form-control py-2 px-3" rows="4"></textarea>
												</div>

												<div>
													<button type="submit" class="btn btn-primary px-3 px-4 py-2">Save</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>

						<div class="tab-pane group-tab fade" role="tabpanel" id="services">
							<ul class="nav nav-pills" hidden>
								<li class="nav-item">
									<a class="ease nav-link position-relative px-4 rounded main-tab-toggle" data-toggle="pill" role="tab" href="#services_all" id="servicesAllToggle">All Services</a>
								</li>
								<li class="nav-item">
									<a class="ease nav-link position-relative px-4 rounded edit-tab-toggle" data-toggle="pill" role="tab" href="#service_edit" id="servicesEditToggle">Edit Service</a>
								</li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane fade main-tab" id="services_all">
									<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
										<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0">All Services</h1>
										<button type="button" class="btn btn-primary edit-trigger-btn" id="addServiceBtn"><i class="fa-solid fa-plus mr-1"></i>Add New</button>
									</div>
									<div class="table-responsive">
										<table class="table" id="servicesTable">
											<thead class="heading text-muted">
												<tr>
													<th>Service</th>
													<th>Excerpt</th>
													<th>Description</th>
													<th></th>
												</tr>
											</thead>

											<tbody></tbody>
										</table>
									</div>
								</div>

								<div class="tab-pane fade edit-tab" id="service_edit">
									<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
										<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0 edit-form-title" id="serviceEditTtl" data-new-text="Add Service" data-edit-text="Edit Service">Add Service</h1>
										<button type="button" class="btn btn-light edit-form-cancel-btn" id="cancelServiceBtn"><i class="fa-solid fa-xmark mr-1"></i> Cancel</button>
									</div>

									<form id="serviceEditForm" class="edit-form" aria-labelledby="serviceEditTtl">
										<div class="form-group">
											<div class="text-center">
												<div id="service_Img" class="bg-img full-image bg-light mb-2 edit-form-img bg-position-center"></div>
												<label class="btn btn-sm btn-light">Upload image <input type="file" name="f" id="service_ImgFI" class="sr-only edit-form-upload"></label>
											</div>
										</div>

										<div class="form-group">
											<label class="heading font-sm text-muted" for="s_name">Service</label>
											<input type="text" name="n" id="s_name" class="ease form-control py-2 px-3" maxlength="50" required>
										</div>

										<div class="form-group">
											<label class="heading font-sm text-muted" for="s_desc">Excerpt <small>- Will be displayed in the website landing page</small></label>
											<input type="text" name="e" id="s_excerpt" class="ease form-control py-2 px-3" maxlength="90" required>
										</div>

										<div class="form-group">
											<label class="heading font-sm text-muted" for="s_desc">Description</label>
											<textarea name="d" id="s_desc" required="" class="ease form-control py-2 px-3" rows="4"></textarea>
										</div>

										<div>
											<button type="submit" class="btn btn-primary px-3 px-4 py-2">Save</button>
										</div>
									</form>
								</div>
							</div>
						</div>

						<div class="tab-pane group-tab fade" role="tabpanel" id="testimonials">
							<ul class="nav nav-pills" hidden>
								<li class="nav-item">
									<a class="ease nav-link position-relative px-4 rounded main-tab-toggle" data-toggle="pill" role="tab" href="#testimonials_all" id="testimonialsAllToggle">All Testimonials</a>
								</li>
								<li class="nav-item">
									<a class="ease nav-link position-relative px-4 rounded edit-tab-toggle" data-toggle="pill" role="tab" href="#testimonial_edit" id="testimonialsEditToggle">Edit Testimonial</a>
								</li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane fade main-tab" id="testimonials_all">
									<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
										<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0">All Testimonials</h1>
										<button type="button" class="btn btn-primary edit-trigger-btn" id="addTestimonialBtn"><i class="fa-solid fa-plus mr-1"></i>Add New</button>
									</div>
									<div class="table-responsive">
										<table class="table" id="testimonialsTable">
											<thead class="heading text-muted">
												<tr>
													<th>Name</th>
													<th>Company</th>
													<th>Quote</th>
													<th></th>
												</tr>
											</thead>

											<tbody></tbody>
										</table>
									</div>
								</div>

								<div class="tab-pane fade edit-tab" id="testimonial_edit">
									<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
										<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0 edit-form-title" id="testimonialEditTtl" data-new-text="Add Testimonial" data-edit-text="Edit Testimonial">Add Testimonial</h1>
										<button type="button" class="btn btn-light edit-form-cancel-btn" id="cancelTestimonialBtn"><i class="fa-solid fa-xmark mr-1"></i> Cancel</button>
									</div>

									<form id="testimonialEditForm" class="edit-form" aria-labelledby="testimonialEditTtl">
										<div class="form-group">
											<label class="heading font-sm text-muted" for="t_name">Name</label>
											<input type="text" name="n" id="t_name" class="ease form-control py-2 px-3" maxlength="50" required>
										</div>

										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label class="heading font-sm text-muted" for="t_pos">Position</label>
													<input type="text" name="p" id="t_pos" class="ease form-control py-2 px-3" maxlength="50" required>
												</div>
											</div>

											<div class="col-sm-6">
												<div class="form-group">
													<label class="heading font-sm text-muted" for="t_company">Company</label>
													<input type="text" name="c" id="t_company" class="ease form-control py-2 px-3" maxlength="50" required>
												</div>
											</div>
										</div>

										<div class="form-group">
											<label class="heading font-sm text-muted" for="t_quote">Quote</label>
											<textarea name="q" id="t_quote" required="" class="ease form-control py-2 px-3" rows="4" maxlength="1000"></textarea>
										</div>

										<div>
											<button type="submit" class="btn btn-primary px-3 px-4 py-2">Save</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</main>
			</div>
		</div>
	</div>
</body>

<script>
	$(document).ready(function() {
		const _team = '/scripts/_team.php';
		const _services = '/scripts/_services.php';
		const _testimonials = '/scripts/_testimonials.php';

		activateNav($('#dBNav'));

		/**
		 * STATS
		 */

		$('#statsForm').submit(function(e) {
			e.preventDefault();

			var o = {
				a: 'set'
			};
			var $this = $(this);

			$('#statsForm input').each(function() {
				if (this.type === 'checkbox') {
					o[this.name] = this.checked ? 1 : 0;
				} else {
					if (!this.checkValidity()) {
						alert(`Error for ${this.labels[0].innerText}: ${this.validationMessage}`);
						return;
					}

					o[this.name] = this.value;
				}
			});

			$.post('../scripts/_stats.php', o, function(d, s) {
				if (d === 'true') alert('Stats updated');
				else {
					console.error(d);
					alert('Failed to update stats');
				}
			});
		});

		$('#statsTabToggle').on('show.bs.tab', function() {
			$('#statsFieldset').attr('disabled', 'disabled');
		});

		$('#statsTabToggle').on('shown.bs.tab', function() {
			$.post('../scripts/_stats.php', {
				a: 'get'
			}, function(d, s) {
				try {
					d = JSON.parse(d);

					$('#statsFieldset').removeAttr('disabled');

					var {
						offices: o,
						clients: c,
						estimate_clients: ec,
						guards: g,
						estimate_guards: eg
					} = d;

					$('#on').val(o);
					$('#cf').val(c);
					$('#gc').val(g);

					$('#cfChk').prop('checked', ec == 0 ? false : true);
					$('#gcChk').prop('checked', eg == 0 ? false : true);
				} catch (error) {
					alert("Couldn't fetch stats");
					console.error(error);
				}
			});
		});

		$('#statsTabToggle').tab('show');

		/**
		 * TEAM
		 */

		// team tab is shown
		$('#teamAllToggle').on('shown.bs.tab', function() {
			$.post(_team, {
				a: 'get'
			}, function(d, s) {
				$('#teamTable tbody').html(d);
			});
		});

		// edit team member
		$('#teamTable').on('click', '[data-member-action="edit"]', function() {
			var t = $(this).closest('[data-member-id]');
			var m = t.attr('data-member-id');

			if (!m) return;

			var i = t.find('.bg-img>img').attr('src');
			var n = t.find('.member-name').text();
			var p = t.find('.member-position').text();
			var d = t.find('.desc-wp').text();

			$('#teamEditTtl').text('Edit Team Member');
			$('#tm_Img').css('background-image', `url(${i})`);
			$('#teamEditForm').attr('data-edit-id', m);

			$('#tm_name').val(n);
			$('#tm_pos').val(p);
			$('#tm_desc').val(d);

			$('#teamEditToggle').tab('show');
		});

		// delete team member
		$('#teamTable').on('click', '[data-member-action="delete"]', function() {
			var t = $(this).closest('[data-member-id]');
			var i = t.attr('data-member-id');

			var n = t.find('.member-name').text();

			var d = confirm(`Delete team member ${n} ?`);
			if (!d) return;

			$.post(_team, {
				a: 'delete',
				i: i,
				n: n
			}, function(d, s) {
				try {

					if (d !== 'true') {
						throw new Error(`team member delete failed; data:${d}`);
					}

					var o = stringToObject(this.data);
					$(`#teamTable tr[data-member-id="${o['i']}"]`).remove();
					alert(`Deleted team member ${o['n']}`);
				} catch (error) {
					console.error(error);
					alert('Failed to delete team member');
				}
			})
		});

		// team form is submitted
		$('#teamEditForm').submit(function(e) {
			e.preventDefault();

			var s = $('#teamEditForm :submit');
			if (s.attr('disabled') !== undefined) return;

			s.attr('disabled', 'disabled');

			var fd = new FormData;

			$('#teamEditForm .form-control').each(function() {
				fd.append(this.name, this.value)
			});

			var i = $(this).attr('data-edit-id');
			var f = $('#tm_ImgFI').prop('files');

			var success, error;

			// if set, then edit
			if (i) {
				fd.append('a', 'edit');
				fd.append('i', i);

				if (f.length !== 0) {
					let file = f[0];
					fd.append('f', file, file.name);
				}

				success = function(d) {
					alert('Team member edited');
				};

				error = function() {
					alert('Failed to edit team member');
				}
			}
			// else add new member
			else {
				fd.append('a', 'new');
				let file = f[0];
				fd.append('f', file, file.name);

				success = function(d) {
					alert('Team member added');
					$('#teamEditForm').trigger('reset');
					$('#tm_Img').css('background-image', '');
				};

				error = function() {
					alert('Failed to add team member');
				};
			}

			ajax(_team, fd, function(d, success, error) {
				try {
					if (d !== 'true') throw new Error(`team member edit failed; data : ${d}`);

					success();
				} catch (e) {
					console.error(e);
					error();
				}
			}, success, error, function(){
				$('#teamEditForm :submit').removeAttr('disabled');
			});
		});

		/**
		 * SERVICES
		 */

		// services tab is shown
		$('#servicesAllToggle').on('shown.bs.tab', function() {
			$.post(_services, {
				a: 'get'
			}, function(d, s) {
				$('#servicesTable tbody').html(d);
			});
		});

		// edit service
		$('#servicesTable').on('click', '[data-service-action="edit"]', function() {
			var t = $(this).closest('[data-service-id]');
			var m = t.attr('data-service-id');

			if (!m) return;

			var i = t.find('.bg-img>img').attr('src');
			var n = t.find('.service-name').text();
			var e = t.find('.service-excerpt').text();
			var d = t.find('.desc-wp').text();

			$('#serviceEditTtl').text('Edit Service');
			$('#service_Img').css('background-image', `url(${i})`);
			$('#serviceEditForm').attr('data-edit-id', m);

			$('#s_name').val(n);
			$('#s_excerpt').val(e);
			$('#s_desc').val(d);

			$('#servicesEditToggle').tab('show');
		});

		// delete service
		$('#servicesTable').on('click', '[data-service-action="delete"]', function() {
			var t = $(this).closest('[data-service-id]');
			var i = t.attr('data-service-id');

			var n = t.find('.service-name').text();

			var d = confirm(`Delete service ${n} ?`);
			if (!d) return;

			$.post(_services, {
				a: 'delete',
				i: i,
				n: n
			}, function(d, s) {
				try {

					if (d !== 'true') {
						throw new Error(`service delete failed; data:${d}`);
					}

					var o = stringToObject(this.data);

					$(`#servicesTable tr[data-service-id="${o['i']}"]`).remove();
					alert(`Deleted service ${o['n']}`);
				} catch (error) {
					console.error(error);
					alert('Failed to delete service');
				}
			})
		});

		// service form is submitted
		$('#serviceEditForm').submit(function(e) {
			e.preventDefault();

			var s = $('#serviceEditForm :submit');
			if (s.attr('disabled') !== undefined) return;

			s.attr('disabled', 'disabled');

			var fd = new FormData;

			$('#serviceEditForm .form-control').each(function() {
				fd.append(this.name, this.value)
			});

			var i = $(this).attr('data-edit-id');
			var f = $('#service_ImgFI').prop('files');

			var success, error;

			// if set, then edit
			if (i) {
				fd.append('a', 'edit');
				fd.append('i', i);

				if (f.length !== 0) {
					let file = f[0];
					fd.append('f', file, file.name);
				}

				success = function(d) {
					alert('Service edited');
				};

				error = function() {
					alert('Failed to edit service');
				}
			}
			// else add new member
			else {
				fd.append('a', 'new');
				let file = f[0];
				fd.append('f', file, file.name);

				success = function(d) {
					alert('Service added');
					$('#serviceEditForm').trigger('reset');
					$('#service_Img').css('background-image', '');
				};

				error = function() {
					alert('Failed to add service');
				};
			}

			ajax(_services, fd, function(d, success, error) {
				try {
					if (d !== 'true') throw new Error(`service edit failed; data : ${d}`);

					success();
				} catch (e) {
					console.error(e);
					error();
				}
			}, success, error, function(){
				$('#serviceEditForm :submit').removeAttr('disabled');
			});
		});

		/**
		 * TESTIMONIALS
		 */

		// testimonials tab is shown
		$('#testimonialsAllToggle').on('shown.bs.tab', function() {
			$.post(_testimonials, {
				a: 'get'
			}, function(d, s) {
				$('#testimonialsTable tbody').html(d);
			});
		});

		// edit testimonial
		$('#testimonialsTable').on('click', '[data-testimonial-action="edit"]', function() {
			var t = $(this).closest('[data-testimonial-id]');
			var i = t.attr('data-testimonial-id');

			if (!i) return;

			var n = t.find('.testimonial-name').text();
			var p = t.find('.testimonial-position').text();
			var c = t.find('.testimonial-company').text();
			var q = t.find('.desc-wp').text();

			$('#testimonialEditTtl').text('Edit Testimonial');
			$('#testimonialEditForm').attr('data-edit-id', i);

			$('#t_name').val(n);
			$('#t_pos').val(p);
			$('#t_company').val(c);
			$('#t_quote').val(q);

			$('#testimonialsEditToggle').tab('show');
		});

		// delete testimonial
		$('#testimonialsTable').on('click', '[data-testimonial-action="delete"]', function() {
			var t = $(this).closest('[data-testimonial-id]');
			var i = t.attr('data-testimonial-id');
			var n = t.find('.testimonial-name').text();

			var d = confirm(`Delete testimonial by ${n} ?`);
			if (!d) return;

			$.post(_testimonials, {
				a: 'delete',
				i: i,
				n: n
			}, function(d, s) {
				try {

					if (d !== 'true') {
						throw new Error(`testimonial delete failed; data:${d}`);
					}

					var o = stringToObject(this.data);

					$(`#testimonialsTable tr[data-testimonial-id="${o['i']}"]`).remove();
					alert(`Deleted testimonial by ${o['n']}`);
				} catch (error) {
					console.error(error);
					alert('Failed to delete testimonial');
				}
			})
		});

		// testimonial form is submitted
		$('#testimonialEditForm').submit(function(e) {
			e.preventDefault();

			var s = $('#testimonialEditForm :submit');
			if (s.attr('disabled') !== undefined) return;

			s.attr('disabled', 'disabled');

			var o = {};

			$('#testimonialEditForm .form-control').each(function() {
				o[this.name] = this.value;
			});

			var i = $(this).attr('data-edit-id');

			// if set, then edit
			if (i) {
				o.a = 'edit';
				o.i = i;
			}
			// else add new member
			else o.a = 'new';

			$.post(_testimonials, o, function(d, s) {
				var ad = stringToObject(this.data);
				var type = ad.a;

				try {
					if (d !== 'true' || s !== 'success') throw new Error(`testimonial edit failed; status: ${s}, data : ${d}`);

					if (type === 'edit') {
						alert('Testimonial edited');
					} else {
						alert('Testimonial added');
						$('#testimonialEditForm').trigger('reset');
					}

				} catch (e) {
					console.error(e);
					if (type === 'edit') {
						alert('Failed to edit testimonial');
					} else {
						alert('Failed to add testimonial');
					}
				} finally {
					$('#testimonialEditForm :submit').removeAttr('disabled');
				}
			});
		});
	});
</script>

</html>