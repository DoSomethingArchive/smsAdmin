	app = angular.module("codeModel", ['ngAnimate']);
	

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
				// $scope.$parent.$parent.$parent.edited = false;
				
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
				
				
				var tip = $scope.$parent.$parent.tip;					//I couldn't figure out a better way to access the collection that the scope is in
																		//than to rise up 2 levels and manually drop into the appropriate collection
				var ctrl = $scope.$parent;
				var a = tip.__comments;
				
				
				$scope.saveCode = function(index) {//Save changes if input can be parsed as a number
					var val = parseInt($scope.view.editableValue);
					if (isNaN(val)) {
						alert('Code should be a number');
						return false;
					}
					$scope.value = val;

					$scope.disableEditor();
					
					
					if (tip.optins) {			
						// console.log($scope.$parent.$parent.key);										
						if(tip.optins[index] === val) return; //Value didn't change so no need to show button 
						ctrl.addChange(tip.__comments, $scope.$parent.$parent.key,  "changed " + tip.optins[index] + " to " + val);
						tip.optins[index] = val;

					} else {
						console.log($scope.$parent.$parent);
						if(tip[index] === val) return; //Value didn't change so no need to show button
						ctrl.addChange(tip.__comments, $scope.$parent.$parent.key,  "changed " + tip[index] + " to " + val);
						tip[index] = val;
					}
					
					$scope.$parent.$parent.$parent.edited = true;
					
					// console.log($scope.$parent.$parent);
					// console.log($scope.$parent.$parent.$parent);
					
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
					// console.log($scope.$parent.$parent);
					ctrl.addChange(tip.__comments, ctrl.$parent.key,  "deleted " + del);
					$scope.$parent.$parent.$parent.edited = true;


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
					
					
					
					var added;
					var ctrl = $scope.$parent;
					var tip = $scope.$parent.tip;
					// console.log($scope.$parent.$parent);
					var obj = $scope.value;
					if (obj.optins) {							//Check if the object is the array type used by tips
						var arr = obj.optins;
						// console.log(arr.length);
						arr[arr.length] = 0;
						added = "added 0";
						
					} else {//object is a real object
						var keyname = prompt("What should the property name be?");
						if (!keyname)	//User didn't input a property name so cancel operation
							return;
						obj[keyname] = 0;
						added = "added " + keyname + " = 0";
					}
					ctrl.addChange(tip.__comments, ctrl.key,  added);
					// console.log($scope.$parent);
					$scope.$parent.$parent.edited = true;

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
					if(!obj || $scope.$parent.campaignTips[obj] !== undefined) return;
					var a = $scope.$parent.campaignTips;
					var ctrl = $scope.$parent;
					a[obj] = {
						"__comments" : "New title",
						optins : [],
						left: 0,
						top: 0
						
					};
					ctrl.addChange(null, obj, "Added new module, mdata = " + obj);
					$timeout(function() {
						setDraggable();
					}, 100);
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
				
				var ctrl = $scope.$parent;
				
				$scope.view = {
					editableValue : $scope.value,
					editableKey : $scope.key, 
					editorEnabled : false
				};

				
				$scope.namePrompt = function() {
					var val = $scope.value;
					var name = prompt('What would you like to rename this?', val);
					if(!name || name === val) return;
					ctrl.addChange(null, null, "Renamed " + val + " to " + name);
					$scope.value = name;
					$scope.view.editableValue = name;

				};
				
				$scope.mdataPrompt = function() {
					
					var val = $scope.key;
					var parent = $scope.$parent.$parent; 
					
					var mdata = prompt('What would you like to change the mdata to?', val);
					if(!mdata || parent.campaignTips[mdata]) return;
					else if(mdata === val) return;
					ctrl.addChange(null, null, "Changed mdata " + val + " to " + mdata);
					parent.campaignTips[mdata] = parent.campaignTips[val];
					delete parent.campaignTips[val];
					$scope.key = mdata;
					$scope.view.editableKey = mdata;
					$timeout(function() {
						setDraggable();
					}, 100);
					$scope.$parent.setPositions();			//Reposition so that no modules move off the screen
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
					
					var ctrl = $scope.$parent;
					var name = $scope.$parent.campaignTips[id].__comments;
					// console.log($scope.$parent.campaignTips[id]);
					var check = confirm("Are you sure you want to delete this campaign?");
					if(check) delete $scope.$parent.campaignTips[id];
					ctrl.addChange(null, null, "Deleted module " + id + " - " + name);
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
		
		$scope.addChange = function(location, mdata, message) {
			var res;
			if(location === null) {
				res = message + "\n";
			}
			else {
				res = "In " + location + "(mdata=" + mdata + "), " + message + "\n";
			}
			$scope.changes += res;
			console.log($scope.changes);
		};
		
		$scope.save = function(model) {
			saveFile(config, $scope.changes);
			$scope.edited = false;
					
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
		
	});
	

	angular.element(document).ready(function() {
		angular.bootstrap('main', ["codeModel"]);
		setDraggable();
	});


	function saveFile(model, log) {
		// startCompare(origin, model);
		// console.log($.diff(origin, model));
		// return;
		// compare(origin, model);
		// return;
		if (model.tips) {
			var destination = 'tips-config.json';
		} else {
			var destination = 'routing-config.json';
		}
		
		
		if(!log) log = "";
		
		$.post('upd', {
			destination : destination,
			file : model,
			log : log
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
			// containment: 'parent',
			stack : ".modules",
		});
		
	}