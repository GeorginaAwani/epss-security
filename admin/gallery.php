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
	<title>EPSS -- Admin | Gallery</title>
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
								<a class="ease nav-link position-relative px-4 rounded main-tab-toggle" data-toggle="pill" role="tab" href="#gallery_all" id="galleryAllToggle">Gallery Media</a>
							</li>
							<li class="nav-item">
								<a class="ease nav-link position-relative px-4 rounded edit-tab-toggle" data-toggle="pill" role="tab" href="#gallery_edit" id="galleryEditToggle">Edit Gallery Media</a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane fade main-tab" id="gallery_all">
								<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
									<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0">Gallery Media</h1>
									<button type="button" class="btn btn-primary edit-trigger-btn" id="addNewsBtn"><i class="fa-solid fa-plus mr-1"></i>Add Media</button>
								</div>
								<div class="table-responsive">
									<table class="table" id="galleryTable">
										<thead class="heading text-muted">
											<tr>
												<th>Media</th>
												<th>Description</th>
												<th></th>
											</tr>
										</thead>

										<tbody></tbody>
									</table>
								</div>
							</div>

							<div class="tab-pane fade edit-tab" id="gallery_edit">
								<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
									<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0 edit-form-title" id="galleryEditTtl" data-new-text="Add Gallery Media" data-edit-text="Edit Gallery Media">Add Gallery Media</h1>
									<button type="button" class="btn btn-light edit-form-cancel-btn" id="cancelEditBtn"><i class="fa-solid fa-xmark mr-1"></i> Cancel</button>
								</div>

								<form id="galleryEditForm" class="edit-form" aria-labelledby="galleryEditTll">
									<div class="form-group">
										<div class="text-center">
											<div id="gallery_Img" class="mb-2 edit-form-img bg-position-center"></div>
											<label class="btn btn-sm btn-light">Upload media <input type="file" name="f" id="gallery_ImgFI" class="sr-only edit-form-upload" data-show-media accept="image/*, video/*"></label>
										</div>
									</div>

									<div class="form-group">
										<label class="heading font-sm text-muted" for="g_desc">Description</label>
										<textarea name="d" id="g_desc" required="" class="ease form-control py-2 px-3" rows="4" maxlength="250"></textarea>
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
		activateNav($('#glrNav'));

		const _gallery = '/scripts/_gallery.php';

		$('#galleryAllToggle').on('show.bs.tab', function() {
			$('#galleryTable tbody').html('<td colspan="3"><div class="text-center"><i class="fa-solid fa-spinner fa-spin"></i></div></td>');
		})

		$('#galleryAllToggle').on('shown.bs.tab', function() {
			$.post(_gallery, {
				a: 'get'
			}, function(d, s) {
				$('#galleryTable tbody').html(d);
			});
		});

		$('#galleryAllToggle').tab('show');

		$('#galleryEditForm').submit(function(e) {
			e.preventDefault();

			var s = $('#galleryEditForm :submit');
			if (s.attr('disabled') !== undefined) return;

			s.attr('disabled', 'disabled');

			var fd = new FormData;

			fd.append('d', $('#g_desc').val());

			var i = $(this).attr('data-edit-id');
			var f = $('#gallery_ImgFI').prop('files');

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
					alert('Gallery edited');
				};

				error = function() {
					alert('Failed to edit gallery');
				}
			}
			// else add new member
			else {
				fd.append('a', 'new');
				let file = f[0];
				fd.append('f', file, file.name);

				success = function(d) {
					alert('Media added to gallery');
					$('#galleryEditForm').trigger('reset');
					$('#gallery_Img').css('background-image', '');
				};

				error = function() {
					alert('Failed to add media to gallery');
				};
			}

			ajax(_gallery, fd, function(d, success, error) {
				try {
					if (d !== 'true') throw new Error(`gallery edit failed; data : ${d}`);

					success();
				} catch (e) {
					console.error(e);
					error();
				}
			}, success, error, function(){
				$('#galleryEditForm :submit').removeAttr('disabled');
			});
		});

		// delete gallery
		$('#galleryTable').on('click', '[data-gallery-action="delete"]', function() {
			var t = $(this).closest('[data-gallery-id]');
			var i = t.attr('data-gallery-id');

			var d = confirm(`Delete gallery?`);
			if (!d) return;

			$.post(_gallery, {
				a: 'delete',
				i: i,
			}, function(d, s) {
				try {

					if (d !== 'true') {
						throw new Error(`gallery delete failed; data:${d}`);
					}

					var o = stringToObject(this.data);

					$(`#galleryTable tr[data-gallery-id="${o['i']}"]`).remove();
					alert(`Deleted gallery`);
				} catch (error) {
					console.error(error);
					alert('Failed to delete gallery');
				}
			})
		});

		// edit gallery
		$('#galleryTable').on('click', '[data-gallery-action="edit"]', function() {
			var t = $(this).closest('[data-gallery-id]');
			var a = t.attr('data-gallery-id');

			if (!a) return;

			var i = t.find('.table-img').clone().removeClass('table-img').addClass('mx-auto img-fluid');
			if(i.is('video')) i.attr('controls', 'true');
			var d = t.find('.desc-wp').text();

			$('#galleryEditTtl').text('Edit Gallery');
			$('#gallery_Img').html(i);
			$('#galleryEditForm').attr('data-edit-id', a);
			$('#gallery_ImgFI').removeAttr('required');

			$('#g_desc').val(d);

			$('#galleryEditToggle').tab('show');
		});
	});
</script>

</html>