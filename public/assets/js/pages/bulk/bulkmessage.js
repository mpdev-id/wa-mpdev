"use strict";

var slider = document.getElementById('slider');
var startSec = 10;
var endSec = 60;

noUiSlider.create(slider, {
	start: [10, 60],
	connect: true,
	range: {
		'min': 1,
		'max': 200
	}
});

slider.noUiSlider.on('update', function(values, handle) {
	console.log(values)
	startSec = values[0];
	endSec = values[1];

	$('#start_time').html(startSec)
	$('#end_time').html(endSec)

});

const maxlenght= 1000;
const base_url= $('#base_url').val();
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const form_link=$('#bulk_message_link').val();

$(document).on('change', '#wa-template', function() {
	var template = $(this).find(':selected').data('template');
	if (template) {
		var text = '';
		if (typeof template === 'string') {
			try {
				template = JSON.parse(template);
			} catch (e) {}
		}
		
		text = template.text || template.caption || '';
		
		$('#wa_message').val(text);
		$('#wa_temp_message').val(text);
	}
});

$(document).on('change', '.csv', function(event) {
	$('.tbody').html('');	
	var files = event.target.files;
	var file = files[0];

	var reader = new FileReader();
	reader.readAsText(file);
	reader.onload = function(event){
		var csv = event.target.result;
		var data = $.csv.toArrays(csv);
		var rows = $('.rows').length;

		let device = $('#device_import').val();
		let device_number = $("#device_import option:selected").text();

		$.each(data, function (key, item) 
		{
			var is_valid = true;
			if (isExist(item[0]) == false) {
				is_valid = false;
			}
			
			if (is_valid == true) {
				var rowId = rows++;
				var html = `<tr class="row${rowId} rows group hover:bg-base-200/30 transition-colors" data-row="${rowId}">
					<td class="py-6">
						<div class="flex items-center gap-3">
							<span class="font-bold text-sm receiver_user">${item[0]}</span>
							<button type="button" data-target=".row${rowId}" class="btn btn-ghost btn-xs text-error remove remove_btn_${rowId}">
								<span class="material-symbols-outlined text-sm">delete</span>
							</button>
							<input type="hidden" name="phone" value="${item[0]}" class="number_${rowId}">
						</div>
					</td>
					<td class="py-6">
						<div class="flex items-center gap-2">
							<span class="material-symbols-outlined text-xs text-base-content/30">devices</span>
							<span class="text-xs font-bold text-base-content/60">${device_number}</span>
							<input type="hidden" name="device" value="${device}" class="device_${rowId}">
						</div>
					</td>
					<td class="py-6">
						<textarea class="textarea textarea-bordered textarea-sm w-full bg-base-100/50 message_${rowId}" name="message" required>${item[1]}</textarea>
					</td>
					<td class="py-6 text-right">
						<span class="badge badge-warning font-bold text-[10px] uppercase tracking-wider px-3 badge_${rowId}">Waiting</span>
					</td>
				</tr>`;

				$('.tbody').append(html);
			}
		});
		$('.total_records').html($('.rows').length);
	}
});

$(document).on('click','.remove',function(){
	var row=$(this).data('target');
	$(row).remove();
	$('.total_records').html($('.rows').length);
});


$(document).on('click', '.add_custom_row', function() {
	var toVal = $('#to').val();
	var msgVal = $('#wa_message').val();
	var deviceId = $('#device_custom').val();
	var deviceName = $("#device_custom option:selected").text();

	if (!toVal) {
		$('.to_alert').show();
		return;
	} else {
		$('.to_alert').hide();
	}

	if (!msgVal) {
		$('.message_alert').show();
		return;
	} else {
		$('.message_alert').hide();
	}

	var numbersArr = toVal.split(",");
	var addedCount = 0;

	$(numbersArr).each(function(index, number) {
		var cleanNumber = number.trim().replace(/\s/g, "");
		if (cleanNumber !== "" && /^\d+$/.test(cleanNumber)) {
			if (isExist(cleanNumber)) {
				var rowsCount = $('.rows').length + 1;
				var html = `<tr class="row${rowsCount} rows group hover:bg-base-200/30 transition-colors" data-row="${rowsCount}">
					<td class="py-6">
						<div class="flex items-center gap-3">
							<span class="font-bold text-sm receiver_user">${cleanNumber}</span>
							<button type="button" data-target=".row${rowsCount}" class="btn btn-ghost btn-xs text-error remove remove_btn_${rowsCount}">
								<span class="material-symbols-outlined text-sm">delete</span>
							</button>
							<input type="hidden" name="phone" value="${cleanNumber}" class="number_${rowsCount}">
						</div>
					</td>
					<td class="py-6">
						<div class="flex items-center gap-2">
							<span class="material-symbols-outlined text-xs text-base-content/30">devices</span>
							<span class="text-xs font-bold text-base-content/60">${deviceName}</span>
							<input type="hidden" name="device" value="${deviceId}" class="device_${rowsCount}">
						</div>
					</td>
					<td class="py-6">
						<textarea class="textarea textarea-bordered textarea-sm w-full bg-base-100/50 message_${rowsCount}" name="message" required>${msgVal}</textarea>
					</td>
					<td class="py-6 text-right">
						<span class="badge badge-warning font-bold text-[10px] uppercase tracking-wider px-3 badge_${rowsCount}">Waiting</span>
					</td>
				</tr>`;

				$('.tbody').append(html);
				addedCount++;
			}
		}
	});

	if (addedCount > 0) {
		$('.total_records').html($('.rows').length);
		$('#to').val('');
		$('#wa_message').val('');
		ToastAlert('success', 'New Contact Added Successfully');
		document.getElementById('addRecord').close();
	}
});

var sendble_rows=[];
function arrayRemove(value) { 
	
	sendble_rows =  sendble_rows.filter(function(ele){ 
		return ele != value; 
	});
}

function isExist(to) {
	var is_valid = true;
	var cleanTo = to.toString().trim().replace(/\s/g, "");
	
	$('.receiver_user').each(function() {
		if ($(this).text().trim().replace(/\s/g, "") === cleanTo) {
			ToastAlert('error', `(${cleanTo}) contact number already added`);
			is_valid = false;
			return false; // break each
		}
	});

	if (is_valid && $('.rows').length >= maxlenght) {
		is_valid = false;
		ToastAlert('error', 'Maximum Sender Limit is ' + maxlenght);
	}

	return is_valid;
}

$(document).on('click','.send_now',function(){
	sendble_rows = [];
	$('.rows').each(function(index, row){
		sendble_rows.push($(this).data('row'));
	});
	
	if (sendble_rows.length > 0) {
		Swal.fire({
			title: 'Launch Campaign?',
			text: `You are about to send ${sendble_rows.length} messages.`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Yes, Launch!',
			confirmButtonColor: '#10b981',
			cancelButtonColor: '#6b7280',
			background: 'rgba(255, 255, 255, 0.9)',
			backdrop: `rgba(0,0,123,0.4)`
		}).then((result) => {
			if (result.isConfirmed) {
				$('.send_now').attr('disabled','disable').addClass('loading');
				submitRequests(sendble_rows[0]);
			}
		});
	}
	else{
		$('.send_now').removeAttr('disabled');
		Swal.fire({
			title: 'Empty Queue',
			text: 'Please add some recipients before launching.',
			icon: 'warning',
			confirmButtonColor: '#10b981'
		});
	}
});

$(document).on('change', '#device_import', function() {
	$(this).val() != '' ? $('.csv_row').show() : $('.csv_row').hide();
});

$(document).on('change', '#selectall', function() {
	if ($(this).is(':checked')) {
		$('.receivers-list').hide();
	} else {
		$('.receivers-list').show();
	}
});

$(document).on('submit', '.contact-list-form', function(e) {
	e.preventDefault();
	var contactsNumbers = [];
	var selectedOptions = $('#wa-contacts').find(':selected');
	
	selectedOptions.each(function() {
		var data = $(this).data('contacts');
		if (data) {
			if (Array.isArray(data)) {
				// It's a group (array of contacts)
				$(data).each(function(index, row) {
					contactsNumbers.push(row.phone);
				});
			} else if (data.phone) {
				// It's a single contact object
				contactsNumbers.push(data.phone);
			}
		}
	});

	if (contactsNumbers.length == 0) {
		Swal.fire({
			title: 'No Recipients',
			text: 'Please select at least one contact or group.',
			icon: 'warning',
			confirmButtonColor: '#10b981'
		});
		return false;
	}

	var rows = $('.rows').length;
	let device = $('#wa-device').val();
	let device_number = $("#wa-device option:selected").text();

	$.each(contactsNumbers, function (key, item) 
	{
		if (isExist(item)) {
			var rowId = rows++;
			var html = `<tr class="row${rowId+1000} rows group hover:bg-base-200/30 transition-colors" data-row="${rowId+1000}">
				<td class="py-6">
					<div class="flex items-center gap-3">
						<span class="font-bold text-sm receiver_user">${item}</span>
						<button type="button" data-target=".row${rowId+1000}" class="btn btn-ghost btn-xs text-error remove remove_btn_${rowId+1000}">
							<span class="material-symbols-outlined text-sm">delete</span>
						</button>
						<input type="hidden" name="phone" value="${item}" class="number_${rowId+1000}">
					</div>
				</td>
				<td class="py-6">
					<div class="flex items-center gap-2">
						<span class="material-symbols-outlined text-xs text-base-content/30">devices</span>
						<span class="text-xs font-bold text-base-content/60">${device_number}</span>
						<input type="hidden" name="device" value="${device}" class="device_${rowId+1000}">
					</div>
				</td>
				<td class="py-6">
					<textarea class="textarea textarea-bordered textarea-sm w-full bg-base-100/50 message_${rowId+1000}" name="message" required>${$('#wa_temp_message').val()}</textarea>
				</td>
				<td class="py-6 text-right">
					<span class="badge badge-warning font-bold text-[10px] uppercase tracking-wider px-3 badge_${rowId+1000}">Waiting</span>
				</td>
			</tr>`;

			$('.tbody').append(html);
		}
	});
	$('.total_records').html($('.rows').length);
	document.getElementById('import_from_contact').close();
});

var total_sent=0;

function submitRequests(param) {
	
	const phone_num=$('.number_'+param).val();
	const message=$('.message_'+param).val();
	const device=$('.device_'+param).val();

	if (phone_num == '') {
		$('.send_now').removeAttr('disabled');
		return ToastAlert('error', 'Add an whatsapp phone number');
	}
	if (message == '') {
		$('.send_now').removeAttr('disabled');
		return ToastAlert('error', 'Add some Message');
	}
	if (device == '') {
		$('.send_now').removeAttr('disabled');
		return ToastAlert('error', 'Select a device');
	}

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: form_link,
		data: {phone: phone_num, device:device,message:message},
		dataType: 'json',
		beforeSend: function() {
			$('.message_'+param).attr('disabled','disable');
			$('.badge_'+param).html('Sending....');
			$('.send_now').attr('disabled','disable');
			$('#add_record').attr('disabled','disable');
			$('#import_record').attr('disabled','disable');
		},
		success: function(response){ 
			
			arrayRemove(param);
			$('.badge_'+param).removeClass('badge-warning badge-error badge-info');
			$('.badge_'+param).addClass('badge-success');
			$('.badge_'+param).html('Sent');
			$('.remove_btn_'+param).remove();
			$('.row'+param).removeClass('rows');
			$('.number_'+param).removeClass('number_'+param);
			$('.message_'+param).removeClass('message_'+param);

			if (sendble_rows.length != 0) {
				var randomTime = Math.floor(Math.random() * (endSec - startSec + 1)) + parseInt(startSec);
				setTimeout(function() {
					submitRequests(sendble_rows[0]);
				}, randomTime * 1000);
			} else {
				$('.send_now').removeAttr('disabled').removeClass('loading');
				Swal.fire({
					title: 'Campaign Finished!',
					text: `Successfully sent ${total_sent} messages.`,
					icon: 'success',
					confirmButtonColor: '#10b981'
				});
				if (typeof congratulations === 'function') congratulations();
			}
			total_sent++;
			$('.total_sent').text(total_sent);

			$('#add_record').removeAttr('disabled');
			$('#import_record').removeAttr('disabled');

		},
		error: function(xhr, status, error) 
		{

			$('.message_'+param).removeAttr('disabled');
			$('.badge_'+param).removeClass('badge-warning badge-success badge-info');
			$('.badge_'+param).addClass('badge-error');
			$('.badge_'+param).html('Failed');
			$('.send_now').removeAttr('disabled');
			$('#add_record').removeAttr('disabled');
			$('#import_record').removeAttr('disabled');
			//Notify('error', xhr);
			arrayRemove(param);
			if (sendble_rows.length != 0) {
				var randomTime = Math.floor(Math.random() * (endSec - startSec + 1)) + parseInt(startSec);
				setTimeout(function() {
					submitRequests(sendble_rows[0]);
				}, randomTime * 1000);
			} else {
				$('.send_now').removeAttr('disabled').removeClass('loading');
				Swal.fire({
					title: 'Campaign Completed',
					text: 'All messages in queue have been processed.',
					icon: 'info',
					confirmButtonColor: '#10b981'
				});
			}
			total_sent++;
			$('.total_sent').text(total_sent);
		}
	});
}

