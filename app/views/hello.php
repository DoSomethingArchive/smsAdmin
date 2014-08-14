<!DOCTYPE >
<html ng-app="myApp">
	<head>
		<title></title>

		<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="//ajax.aspnetcdn.com/ajax/knockout/knockout-3.0.0.js"></script> -->

		<script src="includes/jquery.js"></script>
		<!-- <script src="includes/angular.min.js"></script> -->
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
				margin-right: auto;
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

			.octicon-check {
				color: #9EBF6D;
			}
			.octicon-remove-close {
				color: #B11623;
			}
			.octicon-plus {
				color: #dddddd;
			}

			.med-icon {
				font: normal normal 28px octicons;
			}

			.button {
				cursor: pointer;
			}

		</style>
		<script>


var a = {

"name": "Column Name",
"codes": [
165699,
165701,
165703,
165707,
165709,
165711,
165713,
165715,
165717,
165719
]
};

$('#colHead').text(a.name);
var b = '<div><span>';

$(document).ready(function() {
$.each(a.codes, function(i,v) {
b += '<div><span>' + v + '</span></div>';

});
$('#holder').append(b);

});

function addNewItem() {
	var c = '<div><span>';
	var i = Math.random() * 1000;
	c += i + '</span></div>';
	$('#holder').append(c);
}

		</script>
	</head>
	<body>

		<!-- <div ng-controller = "MyController">
		<div ng-repeat="person in people">
		<div>{{person.name}} <input type="text" ng-model="person.name" /></div>
		</div>

		</div> -->
		
		<button onclick="addNewItem()">Add new item</button>
		<table >
			<tbody>

				<tr>
					<th id="colHead"></th>
				</tr>
				<tr id="holder">

				</tr>

			</tbody>
		</table>
		

	</body>
</html>