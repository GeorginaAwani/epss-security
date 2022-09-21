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
	<title>EPSS -- Admin | Clients</title>
	<link rel="stylesheet" href="/css/admin.css?<?= time() ?>">
	<script src="/js/admin.js?<?= time() ?>"></script>

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
								<a class="ease nav-link position-relative px-4 rounded main-tab-toggle" data-toggle="pill" role="tab" href="#clients_all" id="clientsAllToggle">Clients</a>
							</li>
							<li class="nav-item">
								<a class="ease nav-link position-relative px-4 rounded edit-tab-toggle" data-toggle="pill" role="tab" href="#client_edit" id="clientEditToggle">Edit Client</a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane fade main-tab" id="clients_all">
								<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
									<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0">All Clients</h1>
									<button type="button" class="btn btn-primary edit-trigger-btn" id="addClientBtn"><i class="fa-solid fa-plus mr-1"></i>Add Client</button>
								</div>
								<div class="table-responsive">
									<table class="table" id="clientsTable">
										<thead class="heading text-muted">
											<tr>
												<th>Client</th>
												<th></th>
											</tr>
										</thead>

										<tbody></tbody>
									</table>
								</div>
							</div>

							<div class="tab-pane fade edit-tab" id="client_edit">
								<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
									<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0 edit-form-title" id="clientEditTtl" data-new-text="Add Client" data-edit-text="Edit Client">Add Client</h1>
									<button type="button" class="btn btn-light edit-form-cancel-btn" id="cancelEditBtn"><i class="fa-solid fa-xmark mr-1"></i> Cancel</button>
								</div>

								<form id="clientEditForm" class="edit-form" aria-labelledby="clientEditTll">
									<div class="form-group">
										<div class="text-center">
											<div id="client_Img" class="mb-2 edit-form-img bg-position-center"></div>
											<label class="btn btn-sm btn-light">Upload client logo <input type="file" name="f" id="client_ImgFI" class="sr-only edit-form-upload" data-show-media accept="image/*"></label>
										</div>
									</div>

									<div class="form-group">
										<label class="heading font-sm text-muted" for="c_name">Name</label>
										<input name="n" id="c_name" required="" class="ease form-control py-2 px-3" rows="4" maxlength="100"/>
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
		activateNav($('#cNav'));

		const _clients = '/scripts/_clients.php';

		$('#clientsAllToggle').on('show.bs.tab', function() {
			$('#clientsTable tbody').html('<td colspan="3"><div class="text-center"><i class="fa-solid fa-spinner fa-spin"></i></div></td>');
		})

		$('#clientsAllToggle').on('shown.bs.tab', function() {
			$.post(_clients, {
				a: 'get'
			}, function(d, s) {
				$('#clientsTable tbody').html(d);
			});
		});

		$('#clientsAllToggle').tab('show');

		$('#clientEditForm').submit(function(e) {
			e.preventDefault();

			var s = $('#clientEditForm :submit');
			if (s.attr('disabled') !== undefined) return;

			s.attr('disabled', 'disabled');

			var fd = new FormData;

			fd.append('n', $('#c_name').val());

			var i = $(this).attr('data-edit-id');
			var f = $('#client_ImgFI').prop('files');

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
					alert('Client edited');
				};

				error = function() {
					alert('Failed to edit client');
				}
			}
			// else add new member
			else {
				fd.append('a', 'new');
				let file = f[0];
				fd.append('f', file, file.name);

				success = function(d) {
					alert('Client added');
					$('#clientEditForm').trigger('reset');
					$('#client_Img').css('background-image', '');
				};

				error = function() {
					alert('Failed to add client');
				};
			}

			ajax(_clients, fd, function(d, success, error) {
				try {
					if (d !== 'true') throw new Error(`client edit failed; data : ${d}`);

					success();
				} catch (e) {
					console.error(e);
					error();
				}
			}, success, error, function(){
				$('#clientEditForm :submit').removeAttr('disabled');
			});
		});

		// delete client
		$('#clientsTable').on('click', '[data-client-action="delete"]', function() {
			var t = $(this).closest('[data-client-id]');
			var i = t.attr('data-client-id');

			var d = confirm(`Delete client?`);
			if (!d) return;

			$.post(_clients, {
				a: 'delete',
				i: i,
			}, function(d, s) {
				try {

					if (d !== 'true') {
						throw new Error(`client delete failed; data:${d}`);
					}

					var o = stringToObject(this.data);

					$(`#clientsTable tr[data-client-id="${o['i']}"]`).remove();
					alert(`Deleted client`);
				} catch (error) {
					console.error(error);
					alert('Failed to delete client');
				}
			})
		});

		// edit client
		$('#clientsTable').on('click', '[data-client-action="edit"]', function() {
			var t = $(this).closest('[data-client-id]');
			var a = t.attr('data-client-id');

			if (!a) return;

			var i = t.find('.table-img').clone().removeClass('table-img').addClass('mx-auto img-fluid');
			var d = t.find('.client-name').text();

			$('#clientEditTtl').text('Edit Client');
			$('#client_Img').html(i);
			$('#clientEditForm').attr('data-edit-id', a);
			$('#client_ImgFI').removeAttr('required');

			$('#c_name').val(d);

			$('#clientEditToggle').tab('show');
		});
	});
</script>

</html>