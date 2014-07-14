<!DOCTYPE >
<html>
	<head>
		<title></title>	
		
		<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="//ajax.aspnetcdn.com/ajax/knockout/knockout-3.0.0.js"></script> -->
		
		<script src="includes/jquery.js"></script>
		<script src="includes/angular.min.js"></script>
		<script src="includes/angular-sanitize.js"></script>
		<script type="text/javascript" src="http://angular-ui.github.com/ng-grid/lib/ng-grid.debug.js"></script>
		<!-- <script src="includes/ng-grid-2.0.11.min.js"></script> -->
		<!-- <script src="includes/knockout.js"></script> -->
		
		
		<link rel="stylesheet" href="octicons/octicons.css" />
		<link rel="stylesheet" href="includes/ng-grid.min.css" />
		
		<style>
			html {
				font-family: 'Proxima Nova', 'Trebuchet MS', sans-serif;
				color: #222222;
			}
			
			table {
				border: 1px solid black;
				max-width: 100%;
				min-width: 50%;
				border-collapse: collapse;
				margin-left: auto;
				margin-right:auto;
			}
			
			td {
				/*text-align:center;*/
				line-height: 200%;
				border-right: solid 1px black;
			}
			
			th {
				
				padding: 2% 1%;
				border: 1px solid black;
				background-color: #4e2b63;
				color:white;
			}
			
			.write {
				display:none;
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
				color : #9EBF6D;
			}
			.octicon-remove-close:hover {
				color : #B11623;
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
			
			.med-icon {
				font: normal normal 28px octicons;
			}
			
			.button {
				cursor:pointer;
			}
			
			pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
			.string { color: green; }
			.number { color: darkorange; }
			.boolean { color: blue; }
			.null { color: magenta; }
			.key { color: red; }
			
			.gridStyle {
			    border: 1px solid rgb(212,212,212);
			    width: 400px; 
			    height: 300px
			}

			
		</style>
		
	</head>
	<body>
		@yield('content')
	</body>
</html>