@extends('layouts.default')

@section('content')

<script>
	
	//Get the json config files and make them more manageable to reference
	var config =       <% file_get_contents('ds/lib/ds/tips-config.json') %>;
	var routing =       <% file_get_contents('ds/lib/ds/routing-config.json') %>;

	var campaignTips = config.tips;
	var startCampaignTransitions = routing.startCampaignTransitions;
	var yesNoPaths = routing.yesNoPaths;


	app = angular.module("codeModel", ['ngAnimate']);
	
		app.directive("updateDb", function() {
		var updateTemplate = '<button ng-click="update()" ng-show="$parent.edited" class="btn size">Update Database</button>';
		return {
			restrict : "A",
			replace : true,
			template : updateTemplate,
			scope : {
				value : "=updateDb"
			},
			controller : function($scope) {
				$scope.view = {
					changed : $scope.$parent
				};
				$scope.update = function() {
					console.log($scope.$parent);
					// console.log($scope);
					saveFile(config);
					$scope.$parent.edited = false;
					
					
				}
			}
		} 	
		
	});	

	app.directive("clickToEdit", function() {

		var editorTemplate = '<span class="click-to-edit">' +
								'<span ng-hide="view.editorEnabled" ng-click="enableEditor()">' + '  {{value}} ' + '</span>' + 
								'<span ng-show="view.editorEnabled">' + '<input ng-model="view.editableValue">' + 
									'<span ng-click="saveCode(index)" class="mega-octicon octicon-check med-icon button fade"></span>' + 
									'<span ng-click="disableEditor()" class="mega-octicon octicon-remove-close med-icon button cancel fade"></span>' + 
								'</span>' +
								'<span class="octicon octicon-trashcan button fade right" ng-click="remove(index)"></span>'
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
				$scope.$parent.edited = false;
				
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
				
				
				
				$scope.saveCode = function(index) {//Save changes if input can be parsed as a number
					var val = parseInt($scope.view.editableValue);
					if (isNaN(val)) {
						alert('Code should be a number');
						return false;
					}
					$scope.value = val;

					$scope.disableEditor();

					if ($scope.$parent.$parent.tip.optins) {			//I couldn't figure out a better way to access the collection that the scope is in
																		//than to rise up 2 levels and manually drop into the appropriate collection
																		
						if($scope.$parent.$parent.tip.optins[index] === val) return; //Value didn't change so no need to show button 
						$scope.$parent.$parent.tip.optins[index] = val;

					} else {
						if($scope.$parent.$parent.tip[index] === val) return; //Value didn't change so no need to show button
						$scope.$parent.$parent.tip[index] = val;
					}

					$scope.$parent.$parent.edited = true;
					console.log($scope);
				};
				
				
				
				$scope.remove = function(index) {

					
					if ($scope.$parent.$parent.tip.optins) {	
						$scope.$parent.$parent.tip.optins.splice(index, 1);
					} else {
						delete $scope.$parent.$parent.tip[index];
					}
					
					$scope.$parent.$parent.edited = true;


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
					$scope.$parent.edited = true;

				}
			}
		};
	});
	
	app.directive("editName", function() {

		var otherTemplate = '<span class="editName">' +
								'<span ng-click="namePrompt()">' + '  {{value}} ' + '</span><br />' + 
								'mData: <span ng-click="mdataPrompt()">{{ key }} </span>' +
							'</span>';
								
								
		return {
			restrict : "A",
			replace : true,
			template : otherTemplate,
			scope : {
				value : "=editName",
				key : "=key"
				
			},
			controller : function($scope, $timeout) {
				$scope.view = {
					editableValue : $scope.value,
					editableKey : $scope.key, 
					editorEnabled : false
				};

				
				$scope.namePrompt = function() {
					var val = $scope.value;
					var name = prompt('What would you like to rename this?', val);
					if(!name) return;
					$scope.value = name;
					$scope.view.editableValue = name;

				};
				
				$scope.mdataPrompt = function() {
					// console.log($scope);
					console.log($scope.$parent.$parent.campaignTips);
					var val = $scope.key;
					// console.log($scope.$parent);
					var mdata = prompt('What would you like to change the mdata to?', val);
					if(!mdata) return;
					// $scope.$parent.$parent.campaignTips.replace(val, mdata);
					$scope.$parent.$parent.campaignTips[mdata] = $scope.$parent.$parent.campaignTips[val];
					delete $scope.$parent.$parent.campaignTips[val];
					$scope.key = mdata;
					$scope.view.editableKey = mdata;
					$timeout(function() {
						setDraggable();
					}, 100);
				};
				
				
				
			}
		}	
	});

	app.directive("addNewModule", function() {
		var addTemplate = '<button ng-click="add()">Hi</button>';
		
		return {
			restrict : "A",
			replace : true,
			template : addTemplate,
			scope : {
				value : "=addNewModule"
			},
			controller : function($scope) {
				$scope.add = function() {
					var a = $scope.$parent.campaignTips;
					
					console.log(a);
				}
			}
		} 	
	});
	
	
	app.directive('draggable', function () {
		return {
			restrict: 'A',
			scope: { xpos: '=', ypos: '=' },
			link: function (scope, element, attrs) {
				element.draggable({
					cursor: "move",
					stop: function (event, ui) {
						scope[attrs.xpos] = ui.position.left;
	          			scope[attrs.ypos] = ui.position.top;
	          			scope.$apply();
	          			console.log(scope[attrs.xpos]);
	          			console.log(scope);
					}
				});
			}
		};
	});

	app.controller("MainCtrl", function($scope) {
		
		
		
		$scope.startCampaignTransitions = startCampaignTransitions;
		$scope.yesNoPaths = yesNoPaths;

		$scope.campaignTips = campaignTips;
		
		$scope.sortId = function(campaign) {
			var a = parseInt(campaign.sort);
			console.log(a);
			return a;
		}
		
		$scope.notSorted = function(obj) {		//Display array in the order it's saved, not in Javascript's unhelpful default alphabetic sorting of numbers
			if (!obj) {							
				return [];
			}
			return Object.keys(obj);
		}
		
		$scope.setPositions = function(){
			
			angular.forEach($scope.campaignTips, function(i,v) {
				console.log(i);
			});
			
			// console.log($scope.campaignTips);
		}
		
		$scope.test = {
			"a" : {
				"name" : "Mal",
				"sort" : 2
			},
			"b" : {
				"name" : "Wash",
				"sort" : 1
			}
		};
		
	});
	
	// app.filter('orderObjectBy', function() {
	  // return function(items, field, reverse) {
	    // var filtered = [];
	    // angular.forEach(items, function(item) {
	      // filtered.push(item);
	    // });
	    // filtered.sort(function (a, b) {
	      // return (a[field] > b[field] ? 1 : -1);
	    // });
	    // if(reverse) filtered.reverse();
	    // console.log("here");
	    // return filtered;
	  // };
	// });
	
	app.filter('orderByUnicornHeight', function() {
  return function(obj) {
    
    return [1,2,3];
}});



	angular.element(document).ready(function() {
		angular.bootstrap('main', ["codeModel"]);
		// setDraggable();
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
	
	function setDraggable() {
		$('.modules').draggable({
			containment: 'parent',
			stack : ".modules",
			stop: function (event, ui) {
			   scope[attrs.xpos] = ui.position.left;
			   scope[attrs.ypos] = ui.position.top;
			   scope.$apply();
			}
		});
		
		function setPositions() {
			
		}
		
	}

</script>

<main>

<div ng-controller="MainCtrl" id="moduleHolder">
	
	<div>
		<button ng-click="setPositions()">Pos</button>
	</div>

<table ng-repeat="(key, tip) in campaignTips | orderBy:'key.sort'" class="modules" draggable  >
	
	<tr >
		<th> <span class="button" edit-name="tip.__comments" key="key"></span>
		</th>
	</tr>
	<tr >
		<td>
		<div ng-repeat="key in notSorted(tip.optins)" ng-init="code = tip.optins[key]">

			<input class="write" ng-model="code" />
			<span class="button" click-to-edit="code" index="{{$index}}"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton fade" add-new-item="tip"></span>
		<br />
		<div class="autoMargin">
			<!-- <button onclick="saveFile(config)" class="hide">Update Database</button> -->
			<span update-db="update"></span>
			
		</div>
		</td>
	</tr>
</table>

<div style="clear: both;">
	<button add-new-module="Hi">Hi</button>
</div>

<div style="clear: both;">
	<button onclick="saveFile(config)">
		Save
	</button>
</div>

<code>
		{{ campaignTips | json}}
	</code>

</div>

<table ng-controller="MainCtrl">
	<tr >
		<th ng-repeat="tip in yesNoPaths"> {{tip.__comments}} </th>
	</tr>
	<tr >
		<td ng-repeat="tip in yesNoPaths">
		<div ng-repeat="(key, code) in tip" ng-hide="$first">
			<strong>{{ key }}</strong>:
			<input type="text" ng-model="code" class="write" />
			<span class="button" click-to-edit="code" key="key" index="{{key}}"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton" add-new-item="tip"></span>
		
		</td>
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
			<span class="button" click-to-edit="code" key="key" index="{{key}}"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton" add-new-item="tip"></span></td>
	</tr>
</table>

<div>
	<button onclick="saveFile(routing)">
		Save
	</button>
</div>


<div ng-controller="MainCtrl"> 
	<div ng-repeat="person in test | filter:orderByUnicornHeight:'sort':false">
		{{ person }}
	</div>
</div>



</main>

@stop
