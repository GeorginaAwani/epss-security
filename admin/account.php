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
	<title>EPSS -- Admin | Account</title>
	<link rel="stylesheet" href="../css/admin.css?<?= time() ?>">
	<script src="../js/admin.js?<?= time() ?>"></script>

	<style>
		#adminEditForm .form-control[readonly] {
			background-color: transparent;
			opacity: 0.6;
			font-style: italic;
		}

		#pfp_Img {
			height: 15rem;
			width: 15rem;
		}

		#accountPfp .pfp {
			width: 8rem;
			height: 8rem;
		}

		#accountPfp .pfp img{
			display: none;
		}

		.dot {
			width: 5px;
			height: 5px;
			background-color: currentColor;
		}
	</style>
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
								<a class="ease nav-link position-relative px-4 rounded main-tab-toggle group-tab-toggle" data-toggle="pill" role="tab" href="#account" id="mainToggle">My Account</a>
							</li>
							<li class="nav-item">
								<a class="ease nav-link position-relative px-4 rounded edit-tab-toggle" data-toggle="pill" role="tab" href="#edit" id="editToggle">Edit Users</a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane fade main-tab" id="account">
								<h1 class="heading h3 mb-5">My Account</h1>

								<div class="mb-5">
									<div class="align-items-center d-flex mb-2" id="adminAccount">
										<div id="accountPfp">
											<?= $Administrator->profilePhoto() ?>
										</div>

										<div class="ml-4">
											<div class="account-name heading h5 mb-1 text-black">---</div>
											<div class="align-items-center d-flex mb-2">
												<div class="account-user text-muted">---</div>
												<div class="dot mx-2 rounded-circle text-black-50"></div>
												<div class="account-role font-italic text-black-50">---</div>
											</div>

											<button id="thisEditButton" type="button" class="btn btn-sm btn-warning"><i class="fa-regular fa-pen-to-square mr-2"></i>Edit profile</button>

											<div class="account-date" hidden="">---</div>
										</div>
									</div>
								</div>

								<div><button id="logBtn" class="btn btn-dark px-5 py-2">Log Out</button></div>

								<?php if($_SESSION[Administrator::SESSION_PRIVILEGE] == 1){
									$lockedFields = '';
								?>

								<div class="mb-5 mt-5 pt-5 d-flex justify-content-between align-items-center">
									<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0">Other Users</h1>
									<button type="button" class="btn btn-primary edit-trigger-btn" id="addUserBtn"><i class="fa-solid fa-plus mr-1"></i>Add User</button>
								</div>

								<div class="table-responsive">
									<table class="table" id="usersTable">
										<thead class="heading text-muted">
											<tr>
												<th>User</th>
												<th>Role</th>
												<th>Created</th>
												<th></th>
											</tr>
										</thead>

										<tbody></tbody>
									</table>
								</div>

								<?php
									}
									else $lockedFields = 'readonly disabled';
								?>
							</div>

							<div class="tab-pane fade edit-tab" id="edit">
								<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
									<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0 edit-form-title" id="adminEditTtl" data-new-text="Add Administrator" data-edit-text="Edit Administrator">Add Administrator</h1>
									<button type="button" class="btn btn-light edit-form-cancel-btn" id="cancelEditBtn"><i class="fa-solid fa-xmark mr-1"></i> Cancel</button>
								</div>

								<form id="adminEditForm" class="edit-form" aria-labelledby="adminEditTll">
									<div class="row pt-4">
										<div class="text-center col-sm-4">
											<div id="pfp_Img" class="bg-img bg-light mb-2 mx-auto edit-form-img"></div>
											<label class="btn btn-sm btn-light">Upload image <input type="file" name="i" id="pfp_ImgFI" class="sr-only edit-form-upload"></label>
										</div>
										<div class="flex-fill col-sm-8">
											<div class="form-group">
												<label class="heading font-sm text-muted" for="admin_name">Fullname</label>
												<input type="text" name="n" id="admin_name" class="ease form-control py-2 px-3" maxlength="20" required>
											</div>

											<div class="form-group">
												<label class="heading font-sm text-muted" for="admin_user">Username</label>
												<input type="text" name="u" id="admin_user" class="ease form-control py-2 px-3" maxlength="20" required <?= $lockedFields ?>>
											</div>

											<div class="form-group">
												<label class="heading font-sm text-muted" for="admin_pwd">Password</label>
												<input type="password" name="p" id="admin_pwd" class="ease form-control py-2 px-3" maxlength="20">
											</div>

											<div class="form-group">
												<label class="heading font-sm text-muted" for="admin_role">Role</label>
												<input type="text" name="r" id="admin_role" class="ease form-control py-2 px-3" maxlength="20" required <?= $lockedFields ?>>
											</div>

											<div class="form-group">
												<label class="heading font-sm text-muted" for="admin_date">Created:</label>
												<input type="text" class="form-control px-0" disabled readonly id="admin_date"/>
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
				</main>
			</div>
		</div>
	</div>
</body>

<script>
	$(document).ready(function() {
		'use strict';

		// const _account = '/scripts/_account.php';
		const _account = '/epss/scripts/_account.php';

		$('#mainToggle').on('shown.bs.tab', function() {
			$.post(_account, {a: 'a'}, function(d, s){
				try {
					var acct = JSON.parse(d);

					if(!d) throw new Error('my account failed; data is false');

					var {id: i, user: u, name: n, profile: p, role: r, date: d} = acct;

					$('#adminAccount').attr('data-user-id', i);
					$('#adminAccount .account-name').text(n);
					$('#adminAccount .account-user').text(u);
					if(p) {
						$('#accountPfp .pfp').css('background-image', `url(${p})`);
						$('#accountPfp img').prop('src', p);
					}
					else {
						$('#accountPfp .pfp').css('background-image', '');
						$('#accountPfp img').prop('src', '');
					}
					$('#adminAccount .account-role').text(r);
					$('#adminAccount .account-date').text(d);
				} catch (error) {
					console.error(error);
				}
			});

			$.post(_account, {a: 'u'}, function(d, s){
				$('#usersTable tbody').html(d);
			});
		});

		$('#mainToggle').tab('show');

		$('#logBtn').click(function(){
			var l = confirm('Are you sure you want to log out?');

			if(!l) return;

			$.post(_account, {a: 'l'}, function(d, s){
				if(d !== 'true') alert("Couldn't log you out. Something went wrong");

				location.assign('login.php');
			});
		});

		$('#thisEditButton').click(function(){
			editAccount($('#adminAccount'));
		});

		$('#usersTable').on('click', '[data-user-action="edit"]', function() {
			var t = $(this).closest('[data-user-id]');
			editAccount(t);
		});

		// delete user
		$('#usersTable').on('click', '[data-user-action="delete"]', function() {
			var t = $(this).closest('[data-user-id]');
			var i = t.attr('data-user-id');
			var n = t.find('.account-name').text();

			var d = confirm(`Delete user ${n} ?`);
			if (!d) return;

			$.post(_account, {
				a: 'delete',
				i: i,
				n: n
			}, function(d, s) {
				try {

					if (d !== 'true') {
						throw new Error(`admin user delete failed; data:${d}`);
					}

					var o = stringToObject(this.data);

					$(`#usersTable tr[data-user-id="${o['i']}"]`).remove();
					alert(`Deleted user ${o['n']}`);
				} catch (error) {
					console.error(error);
					alert('Failed to delete user');
				}
			})
		});

		function editAccount(account){
			account = $(account);

			$('#adminEditForm').attr('data-edit-id', account.attr('data-user-id'));
			$('#admin_name').val(account.find('.account-name').text());
			$('#admin_user').val(account.find('.account-user').text());
			$('#admin_pwd').val('');
			$('#admin_role').val(account.find('.account-role').text());
			$('#admin_date').val(account.find('.account-date').text());

			var i = account.find('.bg-img>img');
			if(i.length === 0){
				$('#pfp_Img').css('background-image', '');
			}
			else{
				$('#pfp_Img').css('background-image', `url(${i.prop('src')})`);
			}

			$('#adminEditTtl').text($('#adminEditTtl').attr('data-edit-text'))
			$('#editToggle').tab('show');
		}

		// user form is submitted
		$('#adminEditForm').submit(function(e) {
			e.preventDefault();

			var s = $('#adminEditForm :submit');
			if (s.attr('disabled') !== undefined) return;

			s.attr('disabled', 'disabled');

			var fd = new FormData;

			$('#adminEditForm .form-control').each(function() {
				fd.append(this.name, this.value)
			});

			var i = $(this).attr('data-edit-id');
			var f = $('#pfp_ImgFI').prop('files');

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
					alert('User edited');
				};

				error = function() {
					alert('Failed to edit user');
				}
			}
			// else add new member
			else {
				fd.append('a', 'new');
				let file = f[0];
				fd.append('f', file, file.name);

				success = function(d) {
					alert('User added');
					$('#adminEditForm').trigger('reset');
					$('#pfp_Img').css('background-image', '');
				};

				error = function() {
					alert('Failed to add user');
				};
			}

			ajax(_account, fd, function(d, success, error) {
				try {
					if (d !== 'true') throw new Error(`user edit failed; data : ${d}`);

					success();

				} catch (e) {
					console.error(e);
					error();
				}
			}, success, error, function(){
				$('#adminEditForm :submit').removeAttr('disabled');
			});
		});
	});
</script>

</html>