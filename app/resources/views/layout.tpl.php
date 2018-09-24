<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="{{ $__charset__ }}">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="TinyFile - Solution for tiny file hosting. No download limits, no upload limit. Totaly free.">
		<meta name="keywords" content="file hosting, tiny file, free hosting, no limits, free hosting, file upload, download">
		<title>{{block:title}}TinyFile - file hosting, file upload with no limits, totaly free!{{endblock}}</title>
		<link rel="icon" type="image/png" href="/favicon.png">
		<!--[if IE]>
		<link rel="shortcut icon" href="/favicon.ico"/>
		<![endif]-->
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<meta property="og:locale" content="en">
		<meta property="og:url" content="http://tinyfile.in">
		<meta property="og:title" content="TinyFile - file hosting with no limits, totaly free.">
		<meta property="og:description" content="Solution for tiny file hosting. No download limits, no upload limit. Totaly free.">
		<meta property="og:site_name" content="TinyFile">
		<meta property="og:type" content="website">
		<meta property="og:image" content="http://tinyfile.in/assets/img/logo.png">
		<meta property="og:image:type" content="image/png">
		<meta property="og:image:width" content="725">
		<meta property="og:image:height" content="156">
	</head>
	<body>
		{{block:content}}{{endblock}}
		<script src="/assets/js/jquery.min.js"></script>
		<script src="/assets/js/bootstrap.min.js"></script>
		<script src="/assets/js/jquery.ui.widget.js"></script>
		<script src="/assets/js/jquery.fileupload.js"></script>
		<script src="/assets/js/clipboard.min.js"></script>
		{{block:scripts}}{{endblock}}
	</body>
</html>
