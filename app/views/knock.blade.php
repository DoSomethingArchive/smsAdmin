@extends('layouts.default')

@section('content')

<script>
	
	//Get the json config files and make them more manageable to reference
	var config =       <% file_get_contents('ds/lib/ds/tips-config.json') %>;
	var routing =       <% file_get_contents('ds/lib/ds/routing-config.json') %>;

	var campaignTips = config.tips;
	var startCampaignTransitions = routing.startCampaignTransitions;
	var yesNoPaths = routing.yesNoPaths;



	app = angular.module("codeModel", []);

	app.directive("clickToEdit", function() {

		var editorTemplate = '<span class="click-to-edit"><span class="octicon octicon-trashcan button fade" ng-click="remove(index)"></span>' + 
								'<span ng-hide="view.editorEnabled" ng-click="enableEditor()">' + '  {{value}} ' + '</span>' + 
								'<span ng-show="view.editorEnabled">' + '<input ng-model="view.editableValue">' + 
									'<span ng-click="save(index)" class="mega-octicon octicon-check med-icon button fade"></span>' + 
									'<span ng-click="disableEditor()" class="mega-octicon octicon-remove-close med-icon button cancel fade"></span>' + 
								'</span>' +
							'</span>';

		return {
			restrict : "A",
			replace : true,
			template : editorTemplate,
			scope : {
				value : "=clickToEdit",
				index : '@'
			},
			controller : function($scope) {
				$scope.view = {
					editableValue : $scope.value,
					editorEnabled : false
				};

				$scope.enableEditor = function() {
					$scope.view.editorEnabled = true;
					$scope.view.editableValue = $scope.value;
				};

				$scope.disableEditor = function() {//Close editor without saving changes
					$scope.view.editorEnabled = false;
				};

				$scope.save = function(index) {//Save changes if input can be parsed as a number
					var val = parseInt($scope.view.editableValue);
					if (isNaN(val)) {
						alert('Code should be a number');
						return false;
					}
					$scope.value = val;


					if ($scope.$parent.$parent.tip.optins) {			//I couldn't figure out a better way to access the collection that the scope is in
																		//than to rise up 2 levels and manually drop into the appropriate collection
						$scope.$parent.$parent.tip.optins[index] = val;

					} else {
						$scope.$parent.$parent.tip[index] = val;
					}

					$scope.disableEditor();
				};
				
				
				
				$scope.remove = function(index) {

					
					if ($scope.$parent.$parent.tip.optins) {	
						$scope.$parent.$parent.tip.optins.splice(index, 1);
					} else {
						delete $scope.$parent.$parent.tip[index];
					}


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
					if (obj.optins) {							//Check if the object is the array type used by tips
						var arr = obj.optins;
						arr[arr.length] = 0;
					} else {//object is a real object
						var keyname = prompt("What should the property name be?");
						if (!keyname)	//User didn't input a property name so cancel operation
							return;
						obj[keyname] = 0;
					}

				}
			}
		};
	});

	

	app.controller("MainCtrl", function($scope) {
		$scope.startCampaignTransitions = startCampaignTransitions;
		$scope.yesNoPaths = yesNoPaths;

		$scope.campaignTips = campaignTips;

		$scope.notSorted = function(obj) {		//Display array in the order it's saved, not in Javascript's unhelpful default alphabetic sorting of numbers
			if (!obj) {							
				return [];
			}
			return Object.keys(obj);
		}
	});


	angular.element(document).ready(function() {
		angular.bootstrap('main', ["codeModel"]);
	});


	function saveFile(model) {
		if (model.tips) {
			var destination = 'tips-config.json';
		} else {
			var destination = 'routing-config.json';
		}

		$.post('upd', {
			destination : destination,
			file : model
		}, function(data) {
			console.log(data);
		});
	}

</script>

<main>

<table ng-controller="MainCtrl">
	<tr >
		<th ng-repeat="tip in yesNoPaths"> {{tip.__comments}} </th>
	</tr>
	<tr >
		<td ng-repeat="tip in yesNoPaths">
		<div ng-repeat="(key, code) in tip" ng-hide="$first">
			<strong>{{ key }}</strong>:
			<input type="text" ng-model="code" class="write" />
			<span class="button" click-to-edit="code" index="{{key}}"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton" add-new-item="tip"></span></td>
	</tr>
</table>
<div>
	<button onclick="saveFile(routing)">
		Save
	</button>
</div>

<div ng-controller="MainCtrl">
	<code>
		{{ yesNoPaths | json}}
	</code>
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
			<span class="button" click-to-edit="code" index="{{key}}"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton" add-new-item="tip"></span></td>
	</tr>
</table>

<div>
	<button onclick="saveFile(routing)">
		Save
	</button>
</div>

<div ng-controller="MainCtrl">
	<code>
		{{ startCampaignTransitions | json}}
	</code>
</div>

<div ng-controller="MainCtrl">

<table>

	<tr >
		<th ng-repeat="tip in campaignTips"> {{tip.__comments}} </th>
	</tr>
	<tr >
		<td ng-repeat="tip in campaignTips">
		<div ng-repeat="key in notSorted(tip.optins)" ng-init="code = tip.optins[key]">

			<input class="write" ng-model="code" />
			<span class="button" click-to-edit="code" index="{{$index}}"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton fade" add-new-item="tip"></span></td>
	</tr>
</table>

<div>
	<button onclick="saveFile(config)">
		Save
	</button>
</div>

<code>
		{{ campaignTips | json}}
	</code>

</div>

</main>

@stop
