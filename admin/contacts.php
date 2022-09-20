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
	<title>EPSS -- Admin | Contacts</title>
	<link rel="stylesheet" href="../css/admin.css?<?= time() ?>">
	<script src="../js/admin.js?<?= time() ?>"></script>

</head>

<body class="body-font h-100">
	<div class="container-fluid h-100">
		<div class="row h-100">
			<?php require_once "{$ROOT}/components/sidebar.php" ?>
			<div class="col-lg-9 py-4 px-4">
				<main id="main" class="container px-0 px-lg-2">
					<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
						<h1 class="heading h3 mb-lg-0 mr-3 mr-lg-0">All Contacts</h1>
						<button type="button" class="btn btn-primary" id="refreshContactsBtn"><i class="fa-solid fa-arrows-rotate mr-1"></i>Refresh</button>
					</div>

					<div class="table-responsive">
						<table class="table" id="contactsTable">
							<thead class="heading text-muted">
								<tr>
									<th>Name</th>
									<th>Contact</th>
									<th>Reason</th>
									<th>Message</th>
									<th>Date</th>
								</tr>
							</thead>

							<tbody></tbody>
						</table>
					</div>
				</main>
			</div>
		</div>
	</div>
</body>

<script>
	$(document).ready(function() {
		activateNav($('#ctcNav'));

		// const _contacts = '/scripts/_contacts.php';
		const _contacts = '/epss/scripts/_contacts.php';

		function loadContacts() {
			$('#contactsTable tbody').html('<td colspan="3"><div class="text-center"><i class="fa-solid fa-spinner fa-spin"></i></div></td>');
			$.post(_contacts, {
				a: 'get'
			}, function(d, s) {
				$('#contactsTable tbody').html(d);
			});
		}

		loadContacts();

		$('#refreshContactsBtn').click(function(){
			loadContacts();
		})
	});
</script>

</html>