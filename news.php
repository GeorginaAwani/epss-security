<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once 'components/head.php' ?>
	<title>EPSS | News</title>

	<style>
		#newsList .news-item:not(.pinned) {
			background-color: var(--light);
		}

		.media.bg-img {
			height: 30rem;
		}

		.news-body .news-body-text {
			white-space: pre-wrap;
			line-height: 1.8;
			font-size: 1.1rem;
		}
	</style>
</head>

<body class="body-font">
	<?php include_once 'components/navbar.php' ?>
	
	<div role="banner" class="banner bg-img overlay overlay-black position-relative" style="background-image: url(files/received/9.jpg)" id="bann">
		<div class="container">
			<div class="position-absolute banner-block v-centered">
				<h1 class="font-sm h6 heading mb-3 sub-title d-flex align-items-center text-warning text-uppercase">News</h1>
			</div>
		</div>
	</div>

	<div class="container py-5">
		<div class="row">
			<main class="col-md-8" id="main">
				<ul class="nav nav-pills" hidden id="newsToggle">
					<li class="nav-item">
						<a class="ease nav-link" data-toggle="pill" role="tab" href="#news_all" id="newsAllToggle" data-tab-target="#newsList">All News</a>
					</li>
					<li class="nav-item">
						<a class="ease nav-link" data-toggle="pill" role="tab" href="#news_single" id="newsSingleToggle" data-tab-target="newsSingleWp">Single News</a>
					</li>
				</ul>

				<div class="tab-content">
					<div id="news_all" role="tabpanel" class="tab-pane" aria-labelledby="newsAllToggle">
						<div role="feed" id="newsList"></div>
					</div>

					<div id="news_single" role="tabpanel" class="tab-pane" aria-labelledby="newsSingleToggle">
						<div class="mb-3">
							<button class="btn btn-light px-4 py-3 rounded-0" type="button" id="newsAllSecTgg"><i class="fa-solid fa-arrow-left mr-2"></i>Back to news</button>
						</div>
						<div id="newsSingleWp"></div>
					</div>
				</div>
			</main>
		</div>
	</div>

	<?php include_once 'components/footer.php' ?>
</body>

<script>
	$(document).ready(function() {
		$('#mnNavCllps [href="news.php"]').addClass('active');

		const _news = 'scripts/_news.php';

		const loader = '<div class="text-center h2 mb-0 loader"><i class="fa-solid fa-spinner fa-spin"></i></div>';

		// on show tab, add loader appropriately
		$('#newsToggle [data-toggle="pill"]').on('show.bs.tab', function() {
			var $this = $(this);
			var target = $this.is('[data-tab-target]') ? $this.attr('data-tab-target') : $this.attr('href');

			$(target).html(loader);
		});

		// on shown "all news" tab, load news
		$('#newsAllToggle').on('shown.bs.tab', function() {
			loadNews();
		});

		// on shown "single news" tab, load single news
		$('#newsSingleToggle').on('shown.bs.tab', function() {
			var $this = $(this);
			var i = $this.attr('data-news-id');

			if (!i) return;

			$.post(_news, {
				a: 'single',
				i: i
			}, function(d, s) {
				try {
					var i = stringToObject(this.data);
					i = i.i;
					$('#newsSingleWp').html(d);
					let title = $('#newsSingleWp .news-title').text() + '- EPSS | News';
					document.title = title;
					history.pushState({
						i: i
					}, title, '?id=' + i)
				} catch (error) {
					console.error(error);
				}
			})
		});

		if(location.search){
			let s = stringToObject(location.search.replace('?', ''));
			if(s.id){
				$('#newsSingleToggle').attr('data-news-id', s.id).tab('show');
			} else $('#newsAllToggle').tab('show');
		}
		else $('#newsAllToggle').tab('show');

		function loadNews(i = null) {
			var send = {
				a: 'list'
			};
			if (!!i) send.l = i;

			history.pushState(null, null, location.pathname);
			document.title = 'EPSS | News';

			$.post(_news, send, function(d, s) {
				try {
					$('#newsList .loader').replaceWith(d);
				} catch (error) {
					console.error(error);
				}
			})
		}

		$('#main').on('click', '#newsLd', function() {
			let i = $(this).attr('data-load-id');
			if (!i) return;

			$(this).parent().replaceWith(loader);

			loadNews(i);
		});

		// read more for a news-article is clicked
		$('#main').on('click', '._news_ld', function(e) {
			e.preventDefault();
			var i = $(this).attr('data-news-id');
			if (!i) return;

			$('#newsSingleToggle').attr('data-news-id', i).tab('show');
		});

		$('#newsAllSecTgg').click(function() {
			$('#newsAllToggle').tab('show');
		});
	});
</script>

</html>