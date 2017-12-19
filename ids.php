<!DOCTYPE html>
<html>
	<head>
		<title>Error - access denied</title>
		<meta charset="utf-8">
		<meta name="robots" content="noindex">
		<style type="text/css">
body {
	margin: 0;
	padding: 0;
	font-family: Verdana, Arial, sans-serif;
	background-color: #000;
	color: #f00;
}
#wrapper {
	width: 500px;
	margin: 0 auto;
}
		</style>
	</head>
	<body>
		<div id="wrapper">
			<h1>Access denied!</h1>
			<p>Your request has been blocked for security reasons. Please try again in a few minutes.
Should the problem persist, please email me (see the contact page for details) with the
following unique ID, which will help to identify your request:<br>
			<code><?php echo htmlspecialchars($_SERVER["REDIRECT_UNIQUE_ID"], ENT_QUOTES, 'UTF-8'); ?></code></p>
		</div><!-- wrapper -->
	</body>
</html>
