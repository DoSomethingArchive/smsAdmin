@extends('layouts.default')

@section('content')

<script>
	//Get the json config files and make them more manageable to reference
	var config =  <% file_get_contents('ds/lib/ds/copytips-config.json') %>;
	var routing =  <% file_get_contents('ds/lib/ds/routing-config.json') %>;

	var campaignTips = config.tips;
	var startCampaignTransitions = routing.startCampaignTransitions;
	var yesNoPaths = routing.yesNoPaths;

	var tipsCopy = JSON.parse(angular.toJson(campaignTips));
	var editingMode = false;
	tipsEditMode(campaignTips);

	// function tipsController($scope) {
	//
	// $scope.tips = campaignTips;
	//
	//
	// }

	app = angular.module("codeModel", []);

	app.directive("clickToEdit", function() {

		var editorTemplate = '<span class="click-to-edit">' + '<span ng-hide="view.editorEnabled" ng-click="enableEditor()">' + '{{value}} ' + '</span>' + '<span ng-show="view.editorEnabled">' + '<input ng-model="view.editableValue">' + '<span ng-click="save()" class="mega-octicon octicon-check med-icon button"></span>' + '<span ng-click="disableEditor()" class="mega-octicon octicon-remove-close med-icon button cancel"></span>' + '</span>' + '</span>';

		return {
			restrict : "A",
			replace : true,
			template : editorTemplate,
			scope : {
				value : "=clickToEdit",
			},
			controller : function($scope) {
				$scope.view = {
					editableValue : $scope.value,
					editorEnabled : false
				};

				$scope.enableEditor = function() {
					tipsEditMode(campaignTips);
					$scope.view.editorEnabled = true;
					$scope.view.editableValue = $scope.value;
				};

				$scope.disableEditor = function() {
					$scope.view.editorEnabled = false;
				};

				$scope.save = function() {
					var val = parseInt($scope.view.editableValue);
					if (isNaN(val)) {
						alert('Code should be a number');
						return false;
					}
					$scope.value = val;
					$scope.disableEditor();
				};

			}
		};
	});

	app.directive("addNewItem", function() {

		var addTemplate = '<span class="mega-octicon octicon-plus med-icon button addButton add-new-item" ng-click="add()" ></span>';

		return {
			restrict : "A",
			replace : true,
			template : addTemplate,
			scope : {
				value : "=addNewItem",
			},
			controller : function($scope) {

				$scope.add = function() {
					
					
					var obj = $scope.value;		
					if(obj.optins) {			//Check if the object is the array type
						var arr = obj.optins;
						var index = Object.keys(arr).length;
						console.log(index);
						arr[index] = { str : 0};
					}
					else {
						var keyname = prompt("What should the property name be?");
						if(!keyname) return;
						obj[keyname] = 0;
					}


					
				}
			}
		};
	});

	app.controller("tipsControl", function($scope) {

		$scope.codes = campaignTips;

	});

	app.controller("tipsControlTwo", function($scope) {

		$scope.codes = campaignTips;

	});

	app.controller("transitionsControl", function($scope) {
		$scope.codes = startCampaignTransitions;
	});

	app.controller("yesNoControl", function($scope) {
		$scope.codes = yesNoPaths;
	});

	angular.element(document).ready(function() {
		angular.bootstrap(document, ["codeModel"]);
	});

	function tipsEditMode(tips) {
		if (editingMode)
			return;
		
		editingMode = true;
		$.each(tips, function(i, v) {
			var bigObj = new Object();
			$.each(v.optins, function(i, code) {
				// console.log("Code: " + code);
				var obj = new Object();
				obj.str = code;
				bigObj[i] = obj;
			});
			// return false;
			v.optins = bigObj;

		});
		console.log(tips);
	}

	function tipsDisplayMode(tips) {
		if (!editingMode)
			return;
		editingMode = false;
		$.each(tips, function(i, v) {
			var arr = new Array();
			$.each(v.optins, function(i, code) {
				// console.log("Code: " + code);
				// console.log(code);
				code = code.str;

				// bigObj[i] = obj;
				arr.push(code);
			});
			// return false;
			v.optins = arr;

		});
		console.log(tips);
	}
	
	function saveFile(model) {
		if(model.tips) {
			var destination = 'copytips-config.json';
			tipsDisplayMode(model.tips);
		}
		else {
			var destination = 'copyrouting-config.json';
		}
		
		
		$.post('upd', {
			destination : destination,
			file : model
		}, function(data) {
			console.log(data);
		});
		
	}

</script>

<table ng-controller="yesNoControl">
	<tr >
		<th ng-repeat="tip in codes"> {{tip.__comments}} </th>
	</tr>
	<tr >
		<td ng-repeat="tip in codes">
		<div ng-repeat="(key, code) in tip" ng-hide="$first">
			<strong>{{ key }}</strong>:
			<input type="text" ng-model="code" class="write" />
			<span class="button" click-to-edit="code"></span>
		</div></td>
	</tr>
</table>

<table ng-controller="transitionsControl">
	<tr >
		<th ng-repeat="tip in codes"> {{tip.__comments}} </th>
	</tr>
	<tr >
		<td ng-repeat="tip in codes">
		<div ng-repeat="(key, code) in tip" ng-hide="$first">
			<strong>{{ key }}</strong>:
			<input type="text" ng-model="code" class="write" />
			<span class="button" click-to-edit="code"></span>
		</div>
		<span class="mega-octicon octicon-plus med-icon button addButton" add-new-item="tip"></span>
		</td>
	</tr>
</table>

<div>
	<button onclick="saveFile(routing)">Save</button>
</div>


<button onclick="tipsDisplayMode(campaignTips)">
	Display Mode
</button>

<table ng-controller="tipsControlTwo">

	<tr >
		<th ng-repeat="tip in codes"> {{tip.__comments}} </th>
	</tr>
	<tr >
		<td ng-repeat="tip in codes">
		<div ng-repeat="code in tip.optins">
			<input class="write" ng-model="code" />
			<span class="button" click-to-edit="code.str"></span>
		</div>
		<span class="mega-octicon octicon-plus med-icon button addButton" add-new-item="tip"></span>
		</td>
	</tr>
</table>

<div>
	<button onclick="saveFile(config)">Save</button>
</div>

<!-- <table ng-controller="tipsControlTwo">

<tr >
<th ng-repeat="tip in codes">
{{tip.name}}
</th>
</tr>
<tr >
<td ng-repeat="tip in codes">
<div ng-repeat="code in tip.optins">
<input  ng-model="tip.optins[$index].str" />
<span class="button"></span>

</div>
</td>
</tr>
</table> -->

<div ng-controller="tipsControlTwo">
	<code>
		{{codes | json}}</code>
</div>

@stop
