<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/epss/scripts/functions.php";
# require_once "{$_SERVER['DOCUMENT_ROOT']}/scripts/functions.php";

$ROOT = ROOT;

$Administrator = new Administrator;
if (!$Administrator->isLoggedIn()) header('Location: login.php');
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
	<?php include_once "{$ROOT}/components/head.php" ?>
	<title>EPSS -- Admin | Awards</title>
	<link rel="stylesheet" href="../css/admin.css?<?= time() ?>">
	<script src="../js/admin.js?<?= time() ?>"></script>

</head>

<body class="body-font h-100">
	<div class="container-fluid h-100">
		<div class="row h-100">
			<?php require_once "{$ROOT}/components/sidebar.php" ?>
			<div class="col-lg-9 py-4 px-4">
				<main id="main" class="container px-0 px-lg-2">
					<div class="tab-pane group-tab mt-4 pt-2">
						<ul class="nav nav-pills" hidden>
							<li class="nav-item">
								<a class="ease nav-link position-relative px-4 rounded main-tab-toggle" data-toggle="pill" role="tab" href="#awards_all" id="awardsAllToggle">All Awards</a>
							</li>
							<li class="nav-item">
								<a class="ease nav-link position-relative px-4 rounded edit-tab-toggle" data-toggle="pill" role="tab" href="#award_edit" id="awardEditToggle">Edit Award</a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane fade main-tab" id="awards_all">
								<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
									<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0">All Awards</h1>
									<button type="button" class="btn btn-primary edit-trigger-btn" id="addNewsBtn"><i class="fa-solid fa-plus mr-1"></i>Add Award</button>
								</div>
								<div class="table-responsive">
									<table class="table" id="awardsTable">
										<thead class="heading text-muted">
											<tr>
												<th>Award</th>
												<th>Description</th>
												<th></th>
											</tr>
										</thead>

										<tbody></tbody>
									</table>
								</div>
							</div>

							<div class="tab-pane fade edit-tab" id="award_edit">
								<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
									<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0 edit-form-title" id="awardEditTtl" data-new-text="Add Award" data-edit-text="Edit Award">Add Award</h1>
									<button type="button" class="btn btn-light edit-form-cancel-btn" id="cancelEditBtn"><i class="fa-solid fa-xmark mr-1"></i> Cancel</button>
								</div>

								<form id="awardEditForm" class="edit-form" aria-labelledby="awardEditTll">
									<div class="form-group">
										<div class="text-center">
											<div id="award_Img" class="mb-2 edit-form-img bg-position-center"></div>
											<label class="btn btn-sm btn-light">Upload image <input type="file" name="f" id="award_ImgFI" class="sr-only edit-form-upload" data-show-media accept="image/*"></label>
										</div>
									</div>

									<div class="form-group">
										<label class="heading font-sm text-muted" for="a_desc">Description</label>
										<textarea name="d" id="a_desc" required="" class="ease form-control py-2 px-3" rows="4" maxlength="100"></textarea>
									</div>

									<div>
										<button type="submit" class="btn btn-primary px-3 px-4 py-2">Save</button>
									</div>
								</form>
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
		activateNav($('#awNav'));

		const _awards = '/scripts/_awards.php';

		$('#awardsAllToggle').on('show.bs.tab', function() {
			$('#awardsTable tbody').html('<td colspan="3"><div class="text-center"><i class="fa-solid fa-spinner fa-spin"></i></div></td>');
		})

		$('#awardsAllToggle').on('shown.bs.tab', function() {
			$.post(_awards, {
				a: 'get'
			}, function(d, s) {
				$('#awardsTable tbody').html(d);
			});
		});

		$('#awardsAllToggle').tab('show');

		$('#awardEditForm').submit(function(e) {
			e.preventDefault();

			var s = $('#awardEditForm :submit');
			if (s.attr('disabled') !== undefined) return;

			s.attr('disabled', 'disabled');

			var fd = new FormData;

			fd.append('d', $('#a_desc').val());

			var i = $(this).attr('data-edit-id');
			var f = $('#award_ImgFI').prop('files');

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
					alert('Award edited');
				};

				error = function() {
					alert('Failed to edit award');
				}
			}
			// else add new member
			else {
				fd.append('a', 'new');
				let file = f[0];
				fd.append('f', file, file.name);

				success = function(d) {
					alert('Award added');
					$('#awardEditForm').trigger('reset');
					$('#award_Img').css('background-image', '');
				};

				error = function() {
					alert('Failed to add award');
				};
			}

			ajax(_awards, fd, function(d, success, error) {
				try {
					if (d !== 'true') throw new Error(`award edit failed; data : ${d}`);

					success();
				} catch (e) {
					console.error(e);
					error();
				}
			}, success, error, function(){
				$('#awardEditForm :submit').removeAttr('disabled');
			});
		});

		// delete award
		$('#awardsTable').on('click', '[data-award-action="delete"]', function() {
			var t = $(this).closest('[data-award-id]');
			var i = t.attr('data-award-id');

			var d = confirm(`Delete award?`);
			if (!d) return;

			$.post(_awards, {
				a: 'delete',
				i: i,
			}, function(d, s) {
				try {

					if (d !== 'true') {
						throw new Error(`award delete failed; data:${d}`);
					}

					var o = stringToObject(this.data);

					$(`#awardsTable tr[data-award-id="${o['i']}"]`).remove();
					alert(`Deleted award`);
				} catch (error) {
					console.error(error);
					alert('Failed to delete award');
				}
			})
		});

		// edit award
		$('#awardsTable').on('click', '[data-award-action="edit"]', function() {
			var t = $(this).closest('[data-award-id]');
			var a = t.attr('data-award-id');

			if (!a) return;

			var i = t.find('.table-img').clone().removeClass('table-img').addClass('mx-auto img-fluid');
			var d = t.find('.desc-wp').text();

			$('#awardEditTtl').text('Edit Award');
			$('#award_Img').html(i);
			$('#awardEditForm').attr('data-edit-id', a);
			$('#award_ImgFI').removeAttr('required');

			$('#a_desc').val(d);

			$('#awardEditToggle').tab('show');
		});
	});
</script>

</html>