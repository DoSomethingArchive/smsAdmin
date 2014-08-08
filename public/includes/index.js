	app = angular.module("codeModel", ['ngAnimate']);
	
	//Toggle editing mode for an opt in code (show/hide input box and save/cancel buttons)
	app.directive("editCode", function() {

		
		editUrl = 'includes/templates/edit_code.html';
		
		return {
			//If replace is true, then angular will replace the matched element with the template 
			//ie. if a <span> had an edit-code directive attached to it, it will be replaced by the template
			replace : true,
			templateUrl : editUrl,
			//Use an isolated scope to bind to the parent value and index
			//See here for the docs on isolated scope: https://docs.angularjs.org/guide/directive#isolating-the-scope-of-a-directive
			//This is also a good explanation of the isolated scope: http://onehungrymind.com/angularjs-sticky-notes-pt-2-isolated-scope/
			scope : {
				//Bind inner scope variable 'value' to the parents editCode
				value : "=editCode",
				//'@' sets up a one-way bind from the parent to inner scope. 
				index : '@'
			},
			controller : function($scope) {
				//Bind the display and input box values and default to non-editable
				$scope.view = {
					editableValue : $scope.value,
					editorEnabled : false
				};
				//Show input box to allow editing of codes
				$scope.enableEditor = function() {
					$scope.view.editorEnabled = true;
					$scope.view.editableValue = $scope.value;
				};
				
				//Close editor without saving changes
				$scope.disableEditor = function() {
					$scope.view.editorEnabled = false;
				};
				
				
				//tip is the current campaign
				var tip = $scope.$parent.$parent.tip;
				//ctrl gets the scope of the main controller
				var ctrl = $scope.$parent;
				
				//Save new code if input can be parsed as a number
				$scope.saveCode = function(index) {
					var input = parseInt($scope.view.editableValue);
					if (isNaN(input)) {
						alert('Code should be a number');
						return false;
					}
					$scope.value = input;
					$scope.disableEditor();
					
					//Model is the array type used by optcodes
					if (tip.optins) {			
						//Value didn't change so no need to show button
						if(tip.optins[index] === input) return;  
						//Log change. addChange(Campaign name, campaign mdata, message)
						ctrl.addChange(tip.__comments, $scope.$parent.$parent.key,  "changed " + tip.optins[index] + " to " + input);
						//Update model
						tip.optins[index] = input;

					} 
					//Model is the object type used by routing
					else {
						//Value didn't change so no need to show button
						if(tip[index] === input) return; 
						//Log change
						ctrl.addChange(tip.__comments, $scope.$parent.$parent.key,  "changed " + tip[index] + " to " + input);
						//Update model
						tip[index] = input;
					}
					//An edit has been made so show Save button
					$scope.$parent.$parent.$parent.edited = true;
					
					
				};
				//Delete code
				$scope.remove = function(index) {
					//Set variable to keep track of what was deleted
					var del;
					if (tip.optins) {
						del = tip.optins[index];
						//remove it from array 	
						tip.optins.splice(index, 1);
					} else {
						del = index + ": " + tip[index];
						//delete object property
						delete tip[index];
					}
					
					ctrl.addChange(tip.__comments, ctrl.$parent.key,  "deleted " + del);
					
					$scope.$parent.$parent.$parent.edited = true;


				};

			}
		};
	});
	
	//Add a new code to the current module/campaign
	app.directive("addNewCode", function() {

		var addTemplate = '<span class="mega-octicon octicon-plus med-icon button addButton add-new-code" ng-click="add()" ></span>';

		return {
			//Replace matched element with template
			replace : true,
			template : addTemplate,
			scope : {
				//Bind inner scope to parents addNewCode
				value : "=addNewCode",
			},
			controller : function($scope) {
				
				$scope.add = function() {
					//Keep track of what change
					var added;
					//Access main controller
					var ctrl = $scope.$parent;
					//tip is current campaign
					var tip = $scope.$parent.tip;
					var obj = $scope.value;
					//Check if the object is the array type used by tips
					if (obj.optins) {							
						var arr = obj.optins;
						//Insert 0 at next index
						arr[arr.length] = 0;
						added = "added 0";
						
					} 
					//object is a real object
					else {
						var keyname = prompt("What should the property name be?");
						//User didn't input a property name so cancel operation
						if (!keyname)	
							return;
						//Create new key with entered name and set its value to 0
						obj[keyname] = 0;
						added = "added " + keyname + " = 0";
					}
					//Log change
					ctrl.addChange(tip.__comments, ctrl.key,  added);
					
					$scope.$parent.$parent.edited = true;

				};
			}
		};
	});
	
	
	//Add new module/campaign
	app.directive("addNewModule", function() {
		var addTemplate = '<button class="btn" ng-click="add()">New Campaign</button>';
		
		return {
			// restrict : "A",
			replace : true,
			template : addTemplate,
			scope : {
				value : "=addNewModule"
			},
			controller : function($scope, $timeout) {
				$scope.add = function() {
					var obj = prompt('What is the mdata for the new campaign?');
					//User canceled operation or didn't enter an mdata so return
					if(!obj || $scope.$parent.campaignTips[obj] !== undefined) return;
					
					var tips = $scope.$parent.campaignTips;
					//Access main controller
					var ctrl = $scope.$parent;
					//Create new campaign object
					tips[obj] = {
						"__comments" : "New title",
						optins : [],
						left: 0,
						top: 0
						
					};
					ctrl.edited = true;
					ctrl.addChange(null, obj, "Added new module, mdata = " + obj);
					//Re-set draggable so that the new module is draggable
					//Time delay was necessary because otherwise the draggable wouldn't be applied
					$timeout(function() {
						setDraggable();
					}, 100);
				};
			}
		};
		 	
	});
	
	//Edit campaign name or mdata
	app.directive("editName", function() {

		var otherTemplate = '<span class="editName">' +
								'<span ng-click="namePrompt()">' + '  {{value}} ' + '</span><br />' + 
								'mData: <span ng-click="mdataPrompt()">{{ key }} </span>' +
							'</span>';
								
								
		return {
			// restrict : "A",
			replace : true,
			template : otherTemplate,
			scope : {
				value : "=editName",
				//mdata is the key
				key : "=key"
				
			},
			controller : function($scope, $timeout) {
				
				var ctrl = $scope.$parent;
				
				$scope.view = {
					editableValue : $scope.value,
					editableKey : $scope.key, 
					editorEnabled : false
				};

				//Change the name of the campaign
				$scope.namePrompt = function() {
					var current = $scope.value;
					var newname = prompt('What would you like to rename this?', current);
					//User canceled or didn't change the name, so return
					if(!newname || newname === current) return;
					//Log change
					ctrl.addChange(null, null, "Renamed " + current + " to " + newname);
					$scope.value = newname;
					$scope.view.editableValue = newname;

				};
				
				//Change the mdata of the campaign
				//A bit more complicated than renaming because the the mdata's are the keys
				
				$scope.mdataPrompt = function() {
					
					var current = $scope.key;
					var parent = $scope.$parent.$parent; 
					
					var newMdata = prompt('What would you like to change the mdata to?', current);
					if(!newMdata || parent.campaignTips[newMdata]) return;
					else if(newMdata === current) return;
					ctrl.addChange(null, null, "Changed mdata " + current + " to " + newMdata);
					parent.campaignTips[newMdata] = parent.campaignTips[current];
					delete parent.campaignTips[current];
					$scope.key = newMdata;
					$scope.view.editableKey = newMdata;
					$timeout(function() {
						setDraggable();
					}, 100);
					$scope.$parent.setPositions();			//Reposition so that no modules move off the screen
				};
				
				
				
			}
		};	
	});
	
	//Delete module/campaign
	app.directive("removeModule", function() {
		var template = '<span class="octicon octicon-trashcan button fade right" ng-click="remove(key)"></span>';
		
		return {
			// restrict : "A",
			replace : true,
			template : template,
			scope : {
				value : "=removeModule",
				//mdata is the key
				key : '='
			},
			controller : function($scope) {
				$scope.remove = function(id) {
					//Get the main controller
					var ctrl = $scope.$parent;
					//Get the name of the campaign being deleted for logging
					var name = $scope.$parent.campaignTips[id].__comments;
					//Confirm that the user actually wants to delete the campaign
					var check = confirm("Are you sure you want to delete this campaign?");
					if(check) delete $scope.$parent.campaignTips[id];
					ctrl.addChange(null, null, "Deleted module " + id + " - " + name);
				};
			}
		};
		
	});
	
	//Track module's change in position
	app.directive('draggable', function () {
		return {
			// restrict: 'A',
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
		
		
		//Routing paths
		$scope.startCampaignTransitions = startCampaignTransitions;
		$scope.yesNoPaths = yesNoPaths;
		//Optcode path
		$scope.campaignTips = campaignTips;
		//Variable to keep track of changes. 
		
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
			
			
		};
		
	});
	

	angular.element(document).ready(function() {
		angular.bootstrap('main', ["codeModel"]);
		setDraggable();
	});


	function saveFile(model, log) {

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
	
	
	/**
	 * Use JQuery to keep track of the stacking and containment
	 */
	
	function setDraggable() {
		$('.modules').draggable({
			// containment: 'parent',
			stack : ".modules",
		});
		
	}