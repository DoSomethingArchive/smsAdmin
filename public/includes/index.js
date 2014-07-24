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
					
					
				};
			}
		}; 	
		
	});	

	app.directive("clickToEdit", function() {

		var editorTemplate = '<span class="click-to-edit">' +
								'<span ng-hide="view.editorEnabled" ng-click="enableEditor()">' + '  {{value}} ' + '</span>' + 
								'<span ng-show="view.editorEnabled">' + '<input ng-model="view.editableValue">' + 
									'<span ng-click="saveCode(index)" class="mega-octicon octicon-check med-icon button fade"></span>' + 
									'<span ng-click="disableEditor()" class="mega-octicon octicon-remove-close med-icon button cancel fade"></span>' + 
								'</span>' +
								'<span class="octicon octicon-trashcan button fade right" ng-click="remove(index)"></span>' +
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
				
				
				var tip = $scope.$parent.$parent.tip;
				var loc = tip.__commments;
				var ctrl = $scope.$parent;
				
				
				$scope.saveCode = function(index) {//Save changes if input can be parsed as a number
					var val = parseInt($scope.view.editableValue);
					if (isNaN(val)) {
						alert('Code should be a number');
						return false;
					}
					$scope.value = val;

					$scope.disableEditor();
					
					
					if (tip.optins) {			//I couldn't figure out a better way to access the collection that the scope is in
																		//than to rise up 2 levels and manually drop into the appropriate collection
																		
						if(tip.optins[index] === val) return; //Value didn't change so no need to show button 
						ctrl.addChange(loc, "changed " + tip.optins[index] + " to " + val);
						tip.optins[index] = val;

					} else {
						
						if(tip[index] === val) return; //Value didn't change so no need to show button
						ctrl.addChange(loc, "changed " + tip[index] + " to " + val);
						tip[index] = val;
					}

					$scope.$parent.$parent.edited = true;
					
				};
				
				
				
				$scope.remove = function(index) {

					var del;
					if (tip.optins) {
						del = tip.optins[index]; 	
						tip.optins.splice(index, 1);
					} else {
						del = index + ": " + tip[index];
						delete tip[index];
					}
					ctrl.addChange(tip.__comments, "deleted " + del);
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
						// console.log(arr.length);
						arr[arr.length] = 0;
						
					} else {//object is a real object
						var keyname = prompt("What should the property name be?");
						if (!keyname)	//User didn't input a property name so cancel operation
							return;
						obj[keyname] = 0;
					}
					$scope.$parent.edited = true;

				};
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
					// console.log($scope.$parent.$parent.campaignTips);
					var val = $scope.key;
					// console.log($scope.$parent);
					var mdata = prompt('What would you like to change the mdata to?', val);
					if(!mdata || $scope.$parent.$parent.campaignTips[mdata]) return;
					else if(mdata === val) return;
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
		};	
	});

	app.directive("addNewModule", function() {
		var addTemplate = '<button class="btn" ng-click="add()">New Campaign</button>';
		
		return {
			restrict : "A",
			replace : true,
			template : addTemplate,
			scope : {
				value : "=addNewModule"
			},
			controller : function($scope, $timeout) {
				$scope.add = function() {
					var obj = prompt('What is the mdata for the new campaign?');
					console.log($scope.$parent.campaignTips[obj]);
					if(!obj || $scope.$parent.campaignTips[obj] !== undefined) return;
					var a = $scope.$parent.campaignTips;
					a[obj] = {
						"__comments" : "New title",
						optins : [],
						left: 0,
						top: 0
						
					};
					$timeout(function() {
						setDraggable();
					}, 100);
				};
			}
		};
		 	
	});
	
	app.directive("removeModule", function() {
		var template = '<span class="octicon octicon-trashcan button fade right" ng-click="remove(key)"></span>';
		
		return {
			restrict : "A",
			replace : true,
			template : template,
			scope : {
				value : "=removeModule",
				key : '='
			},
			controller : function($scope) {
				$scope.remove = function(id) {
					var check = confirm("Are you sure you want to delete this campaign?");
					if(check) delete $scope.$parent.campaignTips[id];
				};
			}
		};
		
	});
	
	
	app.directive('draggable', function () {
		return {
			restrict: 'A',
			scope: { xpos: '=', ypos: '=' },
			link: function (scope, element, attrs) {
				element.draggable({
					cursor: "move",
					stop: function (event, ui) {
						
						scope.$apply(function(){
	                        scope.xpos =  ui.position.left;
	                        scope.ypos =  ui.position.top;
                    	});
						
					}
				});
			}
		};
	});

	app.controller("MainCtrl", function($scope) {
		
		
		
		$scope.startCampaignTransitions = startCampaignTransitions;
		$scope.yesNoPaths = yesNoPaths;

		$scope.campaignTips = campaignTips;
		
		$scope.changes = "";
		
		$scope.addChange = function(location, message) {
			$scope.changes += "In " + location + ", " + message + "\n";
			console.log($scope.changes);
		};
		
		$scope.sortId = function(campaign) {
			var a = parseInt(campaign.sort);
			console.log(a);
			return a;
		};
		
		$scope.notSorted = function(obj) {		//Display array in the order it's saved, not in Javascript's unhelpful default alphabetic sorting of numbers
			if (!obj) {							
				return [];
			}
			return Object.keys(obj);
	};
		
		$scope.setPositions = function(){
			
			angular.forEach($scope.campaignTips, function(i,v) {
				i.top = 0; 					
				i.left = 0;
				
			});
			
			// console.log($scope.campaignTips);
		};
		
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
	

	angular.element(document).ready(function() {
		angular.bootstrap('main', ["codeModel"]);
		setDraggable();
	});


	function saveFile(model) {
		// startCompare(origin, model);
		console.log($.diff(origin, model));
		return;
		// compare(origin, model);
		// return;
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
	
	// function compare(original, updated) {
		// angular.forEach(original, function(i,v) {
			// console.log(i + ', ' + v);
		// });
// 		
		// // console.log(original);
		// // console.log(updated);
	// }
	
	/**
	 * Use JQuery to keep track of the stacking and containment
	 */
	
	function setDraggable() {
		$('.modules').draggable({
			containment: 'parent',
			stack : ".modules",
		});
		
	}