<!DOCTYPE >
<html ng-app="myApp">
	<head>
		<title></title>	
		
		<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="//ajax.aspnetcdn.com/ajax/knockout/knockout-3.0.0.js"></script> -->
		
		<script src="includes/jquery.js"></script>
		<script src="includes/angular.min.js"></script>
		<!-- <script src="includes/knockout.js"></script> -->
		
		
		<link rel="stylesheet" href="octicons/octicons.css" />
		
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
				
				padding: 2% 0;
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
			
			.octicon-check {
				color : #9EBF6D;
			}
			.octicon-remove-close {
				color : #B11623;
			}
			.octicon-plus {
				color: #dddddd;
			}		
			
			.med-icon {
				font: normal normal 28px octicons;
			}
			
			.button {
				cursor:pointer;
			}
			
		</style>
		<script>
			var myApp = angular.module('myApp', []);
			
			myApp.run(function($rootScope) {
				$rootScope.name = "Malcolm Reynolds";
			});
			
			myApp.controller('MyController', function($scope) {
				$scope.people = {
					1 : {
						name : "Ender Wiggins"
					},
					2 : {
						name : "Malcolm Reynolds"
					},
					3 : {
						name : "Richard Castle"
					}
				};
			});
			myApp.controller('PlayerController', ['$scope', function($scope) {
				$scope.playing=false;
				$scope.audio = document.createElement('audio');
				
			});
			
		</script>
	</head>
	<body>
		
		<div ng-controller = "MyController">
			<div ng-repeat="person in people">
				<div>{{person.name}} <input type="text" ng-model="person.name" /></div>
			</div>
			
		</div>
		
	</body>
</html>