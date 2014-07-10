@extends('layouts.default')

@section('content')

<script>
	//Get the json config files and make them more manageable to reference
	var config =     <% file_get_contents('ds/lib/ds/copytips-config.json') %>;
	var routing =     <% file_get_contents('ds/lib/ds/copyrouting-config.json') %>;

	var campaignTips = config.tips;
	var startCampaignTransitions = routing.startCampaignTransitions;
	var yesNoPaths = routing.yesNoPaths;

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

		var editorTemplate = '<span class="click-to-edit">' + '<span ng-hide="view.editorEnabled" ng-click="enableEditor()">' + '{{value}} ' + '</span>' + '<span ng-show="view.editorEnabled">' + '<input ng-model="view.editableValue">' + '<span ng-click="save()" class="mega-octicon octicon-check med-icon button fade"></span>' + '<span ng-click="disableEditor()" class="mega-octicon octicon-remove-close med-icon button cancel fade"></span>' + '</span>' + '</span>';

		return {
			restrict : "A",
			replace : true,
			template : editorTemplate,
			scope : {
				value : "=clickToEdit",
				index : '='
			},
			controller : function($scope) {
				$scope.view = {
					editableValue : $scope.value,
					editorEnabled : false
				};

				$scope.enableEditor = function() {
					tipsEditMode(campaignTips);
					//Transform tips arrays into arrays of objects so that angular can update them properly
					$scope.view.editorEnabled = true;
					$scope.view.editableValue = $scope.value;
				};

				$scope.disableEditor = function() {//Close editor without saving changes
					$scope.view.editorEnabled = false;
				};

				$scope.save = function() {//Save changes if input can be parsed as a number
					var val = parseInt($scope.view.editableValue);
					if (isNaN(val)) {
						alert('Code should be a number');
						return false;
					}
					$scope.value = val;
					$scope.disableEditor();
				};

				$scope.remove = function(id) {
					console.log(id);
					console.log($scope.$index);
					// $scope.$delete

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
					if (obj.optins) {//Check if the object is the array type used by tips
						var arr = obj.optins;
						var index = Object.keys(arr).length;
						console.log(index);
						// console.log(index);
						arr[index] = {
							str : 0
						};
					} else {//object is a real object
						var keyname = prompt("What should the property name be?");
						if (!keyname)
							return;
						obj[keyname] = 0;
					}

				}
			}
		};
	});

	app.directive("removestuff", function() {
		return {
			restrict : "E",
			replace : true,
			template : '<span class="octicon octicon-trashcan button fade" ng-click="remove($index)"></span>',
			controller : function($scope) {
				$scope.remove = function(index) {
					
					var arr = $scope.$parent.tip.optins;

					console.log(arr);
	
					delete arr[index];

					console.log(arr);
					arr = reIndex(arr);

				};
			}
		};
	});

	
	app.controller("MainCtrl", function($scope) {
		$scope.startCampaignTransitions = startCampaignTransitions;
		$scope.yesNoPaths = yesNoPaths;
		
		$scope.campaignTips = campaignTips;
		
		$scope.notSorted = function(obj) {
			if (!obj) {
				return [];
			}
			return Object.keys(obj);
		}
		
	});
	


	angular.element(document).ready(function() {
		angular.bootstrap(document, ["codeModel"]);
	});

	function reIndex(arr, orig) {
		
		newArr = Array();
		obj = Object();
		$.each(arr, function(i, v) {
			newArr.push(v);

		});
		$.each(newArr, function(i, v) {
			obj[i] = v;
		});
		return obj;
	}

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
		// console.log(tips);
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
		// console.log(tips);
	}

	function saveFile(model) {
		if (model.tips) {
			var destination = 'copytips-config.json';
			tipsDisplayMode(model.tips);
		} else {
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

<table ng-controller="MainCtrl">
	<tr >
		<th ng-repeat="tip in yesNoPaths"> {{tip.__comments}} </th>
	</tr>
	<tr >
		<td ng-repeat="tip in yesNoPaths">
		<div ng-repeat="(key, code) in tip" ng-hide="$first">
			<strong>{{ key }}</strong>:
			<input type="text" ng-model="code" class="write" />
			<span class="button" click-to-edit="code"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton" add-new-item="tip"></span></td>
	</tr>
</table>
<div>
	<button onclick="saveFile(routing)">
		Save
	</button>
</div>

<table ng-controller="MainCtrl">
	<tr >
		<th ng-repeat="tip in startCampaignTransitions"> {{tip.__comments}} </th>
	</tr>
	<tr >
		<td ng-repeat="tip in startCampaignTransitions">
		<div ng-repeat="(key, code) in tip" ng-hide="$first">
			<strong>{{ key }}</strong>:
			<input type="text" ng-model="code" class="write" />
			<span class="button" click-to-edit="code"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton" add-new-item="tip"></span></td>
	</tr>
</table>

<div>
	<button onclick="saveFile(routing)">
		Save
	</button>
</div>

<button onclick="tipsDisplayMode(campaignTips)">
	Display Mode
</button>

<!-- <table ng-controller="MainCtrl">

	<tr >
		<th ng-repeat="tip in campaignTips"> {{tip.__comments}} </th>
	</tr>
	<tr >
		<td ng-repeat="tip in campaignTips">
		<div ng-repeat="key in notSorted(tip.optins)" ng-init="code = tip.optins[key]">
			
			<removestuff></removestuff>	

			<input class="write" ng-model="code" />
			<span class="button" click-to-edit="code.str"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton fade" add-new-item="tip"></span></td>
	</tr>
</table> -->

<div>
	<button onclick="saveFile(config)">
		Save
	</button>
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

<div ng-controller="MainCtrl">
	<code>
		{{ startCampaignTransitions | json}}
	</code>
</div>

@stop
