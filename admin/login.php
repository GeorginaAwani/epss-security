<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/epss/scripts/functions.php";
# require_once "{$_SERVER['DOCUMENT_ROOT']}/scripts/functions.php";

$ROOT = ROOT;

$Administrator = new Administrator;
if ($Administrator->isLoggedIn()) header('Location: index.php');
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
	<?php include_once "{$ROOT}/components/head.php" ?>
	<title>EPSS -- Admin | Login</title>
	<link rel="stylesheet" href="../css/admin.css?<?= time() ?>">
	<script src="../js/admin.js?<?= time() ?>"></script>

	<style>
		#logo {
			width: 13rem;
		}
	</style>
</head>

<body class="body-font h-100">
	<div class="container-fluid h-100">
		<div class="row h-100">
			<div class="col-md-6 p-5">
				<div>
					<a href="<?=MEDIA_ROOT?>index.php">
						<img src="../logos/logo.jpg" alt="EPSS Logo" id="logo">
					</a>
					<div class="font-sm font-weight-bold ml-2 text-uppercase text-black-50">Admin</div>
				</div>

				<form aria-labelledby="loginFormTtl" id="loginForm" class="mt-5">
					<h1 class="h3 text-black font-weight-bold heading mb-4 pb-2" id="loginFormTtl">Log in</h1>

					<div class="form-group">
						<label for="login_user" class="heading font-sm text-muted">Username</label>
						<input type="text" name="u" id="login_user" class="ease form-control py-2 px-3" required>
					</div>

					<div class="form-group">
						<label for="login_pwd" class="heading font-sm text-muted">Password</label>
						<input type="password" name="p" id="login_pwd" class="ease form-control py-2 px-3" required>
						<div class="mt-2 text-muted small">Contact a super administrator if you have forgotten your username/password</div>
					</div>

					<div>
						<button type="submit" class="btn btn-primary px-4 py-2">Log in</button>
					</div>

					<div class="font-weight-bold mt-2 text-danger" id="loginError" role="status"></div>
				</form>
			</div>
			<div class="col-md-6 bg-dark-blue"></div>
		</div>
	</div>
</body>

<script>
	$(document).ready(function() {
		const _login = '/scripts/_login.php';

		// login form is submitted
		$('#loginForm').submit(function(e) {
			e.preventDefault();
			var submit = $('#loginForm :submit');

			if (submit.attr('disabled') !== undefined) return;

			submit.attr('disabled', 'disabled');

			var u = $('#login_user').val();
			var p = $('#login_pwd').val();

			$.post(_login, {
				u: u,
				p: p
			}, function(d, s) {
				try {
					if (s !== 'success') throw new Error('login failed; ajax error status: ' + s);

					if (d === 'false') {
						$('#loginError').text('Invalid username/password');
					} 
					else if(d === 'true'){
						location.assign('index.php');
					}
					else {
						throw new Error('login failed; error: ' + d);
					}
				} catch (error) {
					alert('Something went wrong');
					console.error(error);
				} finally {
					$('#loginForm :submit').removeAttr('disabled');
				}
			})
		});
	});
</script>

</html>