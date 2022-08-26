<?php
	require_once "{$_SERVER['DOCUMENT_ROOT']}/epss/scripts/functions.php";
	$ROOT = ROOT;
	
	$Administrator = new Administrator;
	if(!$Administrator->isLoggedIn()) header('Location: login.php');
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
	<?php include_once "{$ROOT}/components/head.php" ?>
	<title>EPSS -- Admin | News & Events</title>
	<link rel="stylesheet" href="../css/admin.css?<?= time() ?>">
	<script src="../js/admin.js?<?= time() ?>"></script>

</head>

<body class="body-font h-100">
	<div class="container-fluid h-100">
		<div class="row h-100">
			<?php require_once "{$ROOT}/components/sidebar.php" ?>
			<div class="col-lg-9 py-4 px-4">
				<main id="main" class="container px-0 px-lg-2">
					<div class="tab-pane group-tab mt-4 pt-2" id="news">
						<ul class="nav nav-pills" hidden>
							<li class="nav-item">
								<a class="ease nav-link position-relative px-4 rounded main-tab-toggle" data-toggle="pill" role="tab" href="#news_all" id="newsAllToggle">All Articles</a>
							</li>
							<li class="nav-item">
								<a class="ease nav-link position-relative px-4 rounded edit-tab-toggle" data-toggle="pill" role="tab" href="#news_edit" id="newsEditToggle">Edit Articles</a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane fade main-tab" id="news_all">
								<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
									<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0">All News & Events</h1>
									<button type="button" class="btn btn-primary edit-trigger-btn" id="addNewsBtn"><i class="fa-solid fa-plus mr-1"></i>Add Article</button>
								</div>
								<div class="table-responsive">
									<table class="table" id="newsTable">
										<thead class="heading text-muted">
											<tr>
												<th>Title</th>
												<th>Created</th>
												<th>Body</th>
												<th>Media</th>
												<th></th>
											</tr>
										</thead>

										<tbody></tbody>
									</table>
								</div>
							</div>

							<div class="tab-pane fade edit-tab" id="news_edit">
								<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
									<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0 edit-form-title" id="newsEditTtl" data-new-text="Add Article" data-edit-text="Edit Article">Add Article</h1>
									<button type="button" class="btn btn-light edit-form-cancel-btn" id="cancelNewsMemberBtn"><i class="fa-solid fa-xmark mr-1"></i> Cancel</button>
								</div>

								<form id="newsEditForm" class="edit-form" aria-labelledby="newsEditTll">
									<div class="form-group">
										<label for="t" class="heading font-sm text-muted">Title</label>
										<input type="text" name="t" id="news_title" class="ease form-control py-2 px-3" required maxlength="70">
									</div>

									<div class="form-group">
										<label for="b" class="heading font-sm text-muted">Body</label>
										<textarea name="b" id="news_body" required class="ease form-control py-2 px-3" rows="4"></textarea>
									</div>

									<div class="form-group">
										<div class="font-weight-bold mb-2" id="edtLbl">Event Date <span class="text-muted small font-italic">(optional)</span></div>
										<div>
											<div class="btn-group" role="group" aria-labelledby="edtLbl">
												<input type="date" name="ed" id="ed" class="ease form-control py-2 px-3" aria-label="Event date (optional)" aria-errormessage="edtErr">
												<input type="time" name="et" id="et" class="ease form-control py-2 px-3 ml-3" aria-label="Event time (optional)" aria-errormessage="edtErr">
											</div>
											<div class="font-sm text-danger mt-2" id="edtErr"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="newsFI" class="heading font-sm text-muted">Select Media Files</label>

										<div aria-label="Selected media files" id="newsFO" class="multi-media-output"></div>

										<div class="mt-2">
											<label class="btn btn-light font-sm"><input type="file" name="f" id="newsFI" class="sr-only multi-media-upload" multiple aria-errormessage="fErr" aria-invalid="false" accept="image/*, video/*">Add image/video</label>

											<div class="font-sm text-danger mt-2 multi-media-error" id="fErr"></div>
										</div>
									</div>

									<div class="mt-5">
										<button type="submit" class="btn btn-primary px-3 px-4 py-2">Create</button>
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
		activateNav($('#nENav'));

		const _news = '/scripts/_news.php';

		function getEventDate() {
			var d = $('#ed').prop('valueAsDate');

			var e = $('#edtErr');

			if (d === null) return null;


			var t = $('#et').prop('valueAsDate');

			d.setHours(t.getHours());
			d.setMinutes(t.getMinutes());

			return d;
		}

		$('#newsAllToggle').on('show.bs.tab', function() {
			$('#newsTable tbody').html('<td colspan="3"><div class="text-center"><i class="fa-solid fa-spinner fa-spin"></i></div></td>');
			$('#newsFI').removeAttr('disabled required').parent().removeClass('disabled');
			$('#ed, #et').removeAttr('disabled');
		})

		$('#newsAllToggle').on('shown.bs.tab', function() {
			$.post(_news, {
				a: 'get'
			}, function(d, s) {
				$('#newsTable tbody').html(d);
			});
		});

		$('#newsAllToggle').tab('show');

		// event date/time are updated
		$('#ed, #et').change(function() {
			var e = $('#edtErr');
			var ed = $('#ed').val();

			if (ed !== '' && $('#et').val() === '') {
				$('#et').val('09:00');
			} else if (ed === '') {
				$('#et').val('');
			}

			var d = getEventDate();

			if (d !== null) {
				var n = new Date();

				// difference between dates must be at least 24 hours
				if ((d - n) < 86400000) {
					e.html('Event date must be at least 24 hours from now');
					$('#ed, #et').attr({
						'aria-invalid': 'true'
					});
					return;
				}
			}

			e.html('');
			$('#ed, #et').attr({
				'aria-invalid': 'false'
			});
		});

		$('#newsEditForm').submit(function(e) {
			e.preventDefault();

			var s = $('#newsEditForm :submit');
			if (s.attr('disabled') !== undefined) return;

			s.attr('disabled', 'disabled');

			var fd = new FormData;

			var t = $('#news_title').val();
			var b = $('#news_body').val();

			fd.append('t', t);
			fd.append('b', b);

			var date = getEventDate();
			if (date !== null) fd.append('edt', date.toISOString());

			var i = $(this).attr('data-edit-id');

			var success, error;

			// if id is given, then edit
			if (i) {
				fd.append('a', 'edit');
				fd.append('i', i);

				success = function(d) {
					alert('Article edited');
				};

				error = function() {
					alert('Failed to edit article');
				}
			}
			// else create new article
			else {
				fd.append('a', 'new');
				let fl = mediaFiles.files.length;

				for (var i = 0; i < fl; ++i) {
					let m = mediaFiles.files[i];
					fd.append('f[]', m, m.name);
					fd.append('d[]', mediaFiles.descriptions[i]);
				}

				success = function(d) {
					alert('Article added');
					$('#newsFO').html('');

					$('#newsEditForm').trigger('reset');
					mediaFiles.descriptions = [];
					mediaFiles.files = [];
					mediaFiles.size = 0;
				};

				error = function() {
					alert('Failed to add article');
				};
			}

			ajax(_news, fd, function(d, success, error) {
				try {
					if (d !== 'true') throw new Error(`article form failed; data : ${d}`);

					success();
				} catch (e) {
					console.error(e);
					error();
				}
			}, success, error, function(){
				$('#newsEditForm :submit').removeAttr('disabled');
			});
		});

		// delete article
		$('#newsTable').on('click', '[data-article-action="delete"]', function() {
			var t = $(this).closest('[data-article-id]');
			var i = t.attr('data-article-id');
			var n = t.find('.article-title').text();

			var d = confirm(`Delete article: ${n}?`);
			if (!d) return;

			$.post(_news, {
				a: 'delete',
				i: i,
				n: n
			}, function(d, s) {
				try {
					if (d !== 'true') {
						throw new Error(`article delete failed; data:${d}`);
					}

					var o = stringToObject(this.data);

					$(`#newsTable tr[data-article-id="${o['i']}"]`).remove();
					alert(`Deleted article ${o['n']}`);
				} catch (error) {
					console.error(error);
					alert('Failed to delete article');
				}
			})
		});

		// edit article
		$('#newsTable').on('click', '[data-article-action="edit"]', function() {
			var t = $(this).closest('[data-article-id]');
			var i = t.attr('data-article-id');

			if (!i) return;

			var n = t.find('.article-title').text();
			var b = t.find('.desc-wp').text();
			var e = t.find('.article-event-date');

			if (e.length) {
				let ed = e.attr('data-article-date');
				let d = new Date(ed);
				let month = d.getMonth() + 1;
				let day = d.getDate();
				let hour = d.getHours();
				let min = d.getMinutes();

				if (month < 10) month = `0${month}`;
				if (day < 10) day = `0${day}`;
				if (hour < 10) hour = `0${hour}`;
				if (min < 10) min = `0${min}`;

				let date = `${d.getFullYear()}-${month}-${day}`;
				let time = `${hour}:${min}`;

				$('#ed').removeAttr('disabled').val(date);
				$('#et').removeAttr('disabled').val(time);
			} else {
				$('#ed, #et').attr('disabled', 'disabled');
			}

			$('#news_title').val(n);
			$('#news_body').val(b);

			$('#newsEditTtl').text('Edit Article');
			$('#newsEditForm').attr('data-edit-id', i);


			$('#newsFI').attr('disabled', 'disabled').parent().addClass('disabled');

			$('#newsEditToggle').tab('show');
		});
	});
</script>

</html>