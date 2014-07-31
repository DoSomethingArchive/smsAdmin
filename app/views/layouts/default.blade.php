<!DOCTYPE >
<html>
	<head>
		<title></title>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular-animate.js"></script>
		<script src="includes/index.js" type="text/javascript"></script>
		
		<!-- <script src="requirements/json_compare.js" type="text/javascript"></script> -->
		<!-- <script src="requirements/diff.js" type="text/javascript"></script> -->
		<!-- <script src="//ajax.aspnetcdn.com/ajax/knockout/knockout-3.0.0.js"></script> -->

		<link rel="stylesheet" href="octicons/octicons.css" />
		<link rel="stylesheet" href="requirements/neue.css" />
		<link rel="stylesheet" href="requirements/Proxima Nova/ProximaNova-Reg.otf" />
		<!-- <script src="includes/jquery.js"></script> -->
		<!-- <script src="includes/angular.min.js"></script> -->
		<!-- <script src="includes/angular-sanitize.js"></script> -->

		<style>
			@font-face {
				font-family: Proxima Nova;
				src: url('includes/Proxima Nova/ProximaNova-Reg.otf');
			}
		
			html {
				font-family: 'Proxima Nova', 'Trebuchet MS', sans-serif;
				color: #222222;
				
			}
			
			header {
				color: white;
				text-align:center;
				padding: 2%;
				font-size: 150%;
				background: #4e2b63;
				background-image: linear-gradient(rgba(0, 0, 0, 0) 20%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0.5) 70%, rgba(0, 0, 0, 0.85) 100%);
			}

			table {
				/*border: 1px solid black;*/
				max-width: 100%;
				/*min-width: 50%;*/
				border-collapse: collapse;
				margin-left: auto;
				margin-right: auto;
			}

			td {
				/*text-align:center;*/
				line-height: 200%;
				padding: 5%;
				/*border-right: solid 1px black;*/
			}

			th {

				padding: 2% 1%;
				border: 1px solid black;
				background-color: #4e2b63;
				color: white;
			}

			.write {
				display: none;
				width: 100%;
			}

			.write input {
				width: 50;
			}

			.hidden {
				display: none;
			}

			.save {

			}

			.save span {
				padding-left: 15%;
			}

			.octicon-check, .octicon-remove-close {
				color: #dddddd;
			}

			.octicon-check:hover {
				color: #9EBF6D;
			}
			.octicon-remove-close:hover {
				color: #B11623;
			}
			.octicon-plus, .octicon-trashcan {
				color: #dddddd;
			}
			.octicon-plus:hover, .octicon-trashcan:hover {
				color: #333333;
			}
			

			.fade {
				-o-transition: .5s;
				-webkit-transition: .5s;
				-moz-transition: .5s;
				transition: .5s;
			}
			
			.right {
				float:right;
			}

			.med-icon {
				font: normal normal 28px octicons;
			}

			.button {
				cursor: pointer;
			}
			
			.autoMargin {
				margin-left: auto;
				margin-right: auto;
				text-align:center;
			}

			pre {
				outline: 1px solid #ccc;
				padding: 5px;
				margin: 5px;
			}

			.modules {
				float: left;
				margin: 1%;
				display: inline;
				background-color: #f9f9f9;
				
				border-bottom: solid 2px #EEE;
				border-right: solid 1px #EEE;
				cursor: move;
				
			}
			
			.modules:active {
				background-color: #f1f1f1;
				opacity: .8;
				/*box-shadow: 3px 3px rgba(0,0,0,.4);*/
				
			}
			
			#moduleHolder {
				/*border: 1px solid black;*/
			}
			
			.hide {
				display: none;
			}
			
			
			.ng-hide {
			  opacity:0;
			}
			
			.size {
				font-size: 14px;
				font-weight: normal;
			}
			
			.sectionHeader {
				background: black;
				text-align: center;
			}
			
			.center {
				text-align:center;
			}

			

		</style>

	</head>
	<body>
		
		<header>
			SMS Admin
		</header>
		
		@yield('content')
	</body>
</html>