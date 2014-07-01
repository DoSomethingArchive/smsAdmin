@extends('layouts.default')

@section('content')

<script>
	var config = <% file_get_contents('ds/lib/ds/tips-config.json') %>;
	var routing = <% file_get_contents('ds/lib/ds/routing-config.json') %>;
	
	var mytips = config.tips;
	var startCampaignTransitions = routing.startCampaignTransitions;
	var yesNoPaths = routing.yesNoPaths;
	// console.log(startCampaignTransitions);
	// console.log(yesNoPaths);

	var html;

	$(document).ready(function() {

		// data += setupTable(yesNoPaths, data);

		var table = setupTable(yesNoPaths);
		var table2 = setupTable(startCampaignTransitions);
		var table3 = setupTable(mytips);

		$('#display').append(table);
		$('#display').append(table2);
		$('#display').append(table3);

		$('.readOnly').on('click', function() {

			toggle($(this).parent()[0].id);

		});
		$('.cancel').on('click', function() {
			toggle($(this).parent().parent().parent()[0].id);
		});
		$('.addButton').on('click', function() {
			// console.log($(this).data("attribute"));
			var id = $(this)[0].id;
			var data = $(this).data("attribute");
			$(this).data("attribute", data + 1);
			var el = generateInput(id, data, "XXXXX") + '<br />';
			$(this).prev().append(el);
			var read = '#' + id + '-' + data + 'read';
			// var cancel = ''
			$(read).on('click', function() {
				console.log(this);
				toggle($(this).parent()[0].id);
			});
			$('.cancel').off('click').on('click', function() {
				toggle($(this).parent().parent().parent()[0].id);
			});

			// console.log(el);
			// $('#ThumbWars2014-DOtips').data("attribute", ($('#ThumbWars2014-DOtips').data('attribute')+1))

		});

	});

	function setupTable(source) {

		var table;
		var headers = "<tr>";
		var data = "<tr>";
		var lastIndex;
		var path = "config.tips";

		$.each(source, function(i, v) {
			var item = v.__comments;
			item = item.replace(/\s*[.]*/g, "");
			headers += '<th>' + item + '</th>';
			data += "<td><div class='hi'>";
			var pathLevel1 = path + "[" + i + "]";
			
			// html = '<tr><th>' + v.name + '</th><tr>';
			if (v.optins != undefined) {
				$.each(v.optins, function(index, m) {
					var pathLevel2 = pathLevel1 + ".optins" + "[" + index + "]";
					// data += m + '<br />';
					// data += generateInput(item, index, m) + '<br />';
					data += generateInput2(item, index, m, pathLevel2) + '<br />';
					lastIndex = index;
				});
				lastIndex++;
				// data += generateInput(item, lastIndex, 'None') + '<br />';
				data += '</div>';
				data += generateAddButton(item, lastIndex) + '<br />';
			} else {
				if (v.incomingPath != undefined) {
					data += "Incoming Path: " + generateInput(item, 0, v.incomingPath) + '<br />';
					data += "Yes Code: " + generateInput(item, 1, v.yes) + '<br />';
					data += "No Code: " + generateInput(item, 2, v.no) + '<br />';
					data += '</div>';
				} else {
					// console.log(i);
					// console.log(v);
					// data += generateInput(item, 0, v.incomingPath) + '<br />';
					data += "Opt in Code: " + generateInput(item, 0, v.optin) + '<br />';
					data += "Opt out Code: " + generateInput(item, 1, v.optout) + '<br />';
					// data += 'No data';
					data += '</div>';

				}
			}
			data += '</td>';
			// table += html;
			// $('#display').append(html);
			// n = v;
			// console.log(html);
		});

		headers += '</tr>';
		data += '</tr>';
		table = '<table>' + headers + data + '</table>';

		return table;
		// return data;
	}
	
	function setupTable2(source) {

		var table;
		var headers = "<tr>";
		var data = "<tr>";
		var lastIndex;
		

		$.each(source, function(i, v) {
			var item = v.__comments;
			item = item.replace(/\s*[.]*/g, "");
			headers += '<th>' + item + '</th>';
			data += "<td><div class='hi'>";
			
			
			// html = '<tr><th>' + v.name + '</th><tr>';
			if (v.optins != undefined) {
				$.each(v.optins, function(index, m) {
					
					// data += m + '<br />';
					// data += generateInput(item, index, m) + '<br />';
					data += generateInput2(item, index, m) + '<br />';
					lastIndex = index;
				});
				lastIndex++;
				// data += generateInput(item, lastIndex, 'None') + '<br />';
				data += '</div>';
				data += generateAddButton(item, lastIndex) + '<br />';
			} else {
				if (v.incomingPath != undefined) {
					data += "Incoming Path: " + generateInput(item, 0, v.incomingPath) + '<br />';
					data += "Yes Code: " + generateInput(item, 1, v.yes) + '<br />';
					data += "No Code: " + generateInput(item, 2, v.no) + '<br />';
					data += '</div>';
				} else {
					// console.log(i);
					// console.log(v);
					// data += generateInput(item, 0, v.incomingPath) + '<br />';
					data += "Opt in Code: " + generateInput(item, 0, v.optin) + '<br />';
					data += "Opt out Code: " + generateInput(item, 1, v.optout) + '<br />';
					// data += 'No data';
					data += '</div>';

				}
			}
			data += '</td>';
			// table += html;
			// $('#display').append(html);
			// n = v;
			// console.log(html);
		});

		headers += '</tr>';
		data += '</tr>';
		table = '<table>' + headers + data + '</table>';

		return table;
		// return data;
	}
	
	function toggle(id) {
		var read = '#' + id + 'read';
		var write = '#' + id + 'write';
		// console.log(read);
		// console.log(write);
		// console.log($(read));
		$(read).toggle();
		$(write).toggle();

	}

	function generateInput(category, arrayIndex, value) {
		ident = category + '-' + arrayIndex + "-";
		value = typeof value !== 'undefined' ? value : "";
		var code = "<span id = '" + ident + "'>";
		var readOnly = "<span class='readOnly button' id = '" + ident + "read'>" + value + "</span>";
		var input = "<span class = 'write' id = '" + ident + "write'><input  type='text' value = '" + value + "'/> <span class = 'save'><span class='mega-octicon octicon-check med-icon button'></span><span class='mega-octicon octicon-remove-close med-icon button cancel'></span></span></span>";

		/**
		 * Might switch over to contenteditable instead of discrete elements for read and write
		 */

		// var editable = "<span contenteditable = true class='readOnly button' id = '" + ident + "read'>" + value + "</span>";
		// editable += "<span class = 'save hidden' ><span class='mega-octicon octicon-check med-icon button'></span><span class='mega-octicon octicon-remove-close med-icon button cancel'></span></span></span>";
		// code += editable + "</span>";

		code += readOnly + input + "</span>";
		return code;
	}

	function generateInput2(category, arrayIndex, value, path) {
			// console.log(files);
			// console.log(path);
			var path = "." + path;
			// console.log(files.path);
			ident = category + '-' + arrayIndex + "-";
			value = typeof value !== 'undefined' ? value : "";
			var code = "<span id = '" + ident + "'>";
			var readOnly = "<span class='readOnly button' id = '" + ident + "read'>" + value + "</span>";
			var input = "<span class = 'write' id = '" + ident + "write'><input  type='text' value = '" + value + "'/> <span class = 'save'><span class='mega-octicon octicon-check med-icon button'></span><span class='mega-octicon octicon-remove-close med-icon button cancel'></span></span></span>";
	
			/**
			 * Might switch over to contenteditable instead of discrete elements for read and write
			 */
	
			// var editable = "<span contenteditable = true class='readOnly button' id = '" + ident + "read'>" + value + "</span>";
			// editable += "<span class = 'save hidden' ><span class='mega-octicon octicon-check med-icon button'></span><span class='mega-octicon octicon-remove-close med-icon button cancel'></span></span></span>";
			// code += editable + "</span>";
	
			code += readOnly + input + "</span>";
			return code;
		}

	function generateAddButton(category, arrayIndex) {
		ident = category;
		var button = '<span id="' + ident + '" data-attribute="' + arrayIndex + '" class="mega-octicon octicon-plus med-icon button addButton"></span>';
		return button;
	}

</script>

<div id="display">

</div>

<span class="mega-octicon octicon-plus med-icon"></span>
<span class="mega-octicon octicon-remove-close med-icon"></span>

@stop
