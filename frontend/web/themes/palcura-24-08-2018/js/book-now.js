function remove_pet_singles(petSingleCount, petCount) {
    for (var i = petSingleCount; i > petCount; i--) {
        $(".pal_single[data-id='" + i + "']").slideUp().remove();
    }
}
// function to add autocomplete feature on any input box ( passed as jquery DOM object ($this) in parameter)
function palAutoComplete($this) {
    $this.autocomplete({
        minLength: 1,
        source: function (request, response) {
            $this.parent().find('.loading span').show();
            $.getJSON(siteUrl+"/bookings/get-pets?search=" + request.term, request, function (data, status, xhr) {
                $this.parent().find('.loading span').hide();
                var pets = [];
                // no need to show already selected pets
                $.each(data, function (k, v) {
                    if(!window.selectedPets[v.id]){
                        pets.push(v);
                    }
                });
                response(pets); 
            });
        },
        select: function (event, ui) {
            $(this).attr('readonly', 'readonly');
            $(this).parent().parent().parent().find("[name='palDetails[type][]']").val(ui.item.type).attr('readonly', 'readonly');
            $(this).parent().parent().parent().find("[name='palDetails[care_note][]']").text(ui.item.care_note);
            $(this).parent().parent().parent().find("[name='palDetails[id][]']").val(ui.item.id);
            $(this).parent().parent().parent().find("._remove_pal").show();
            window.selectedPets[ui.item.id] = ui.item.value;
        }
    });
}

function preFillValues($this, item){
 	$this.attr('readonly', 'readonly');
 	$this.val(item.name);
    $this.parent().parent().parent().find("[name='palDetails[type][]']").val(item.type).attr('readonly', 'readonly');
    $this.parent().parent().parent().find("[name='palDetails[care_note][]']").text(item.care_note);
    $this.parent().parent().parent().find("[name='palDetails[id][]']").val(item.id);
    $this.parent().parent().parent().find("._remove_pal").show();
    window.selectedPets[item.id] = item.name;
}

function add_pet_singles(petSingleCount, petCount) {
    for (var i = petSingleCount + 1; i <= petCount; i++) {
        var clone = $('.pal_single_org').clone();
        clone.removeClass('pal_single_org hidden').addClass('pal_single');
        clone.attr('data-id', i);
        palAutoComplete(clone.find('.booking_pal_name'));
        $('.pal_single_container').append(clone);
    }
}

$('document').ready(function () {
    // This window variable holds all the already selected pets
    window.selectedPets = {};
    if(fillpets== 1){
	$('.pal_single_container [name="palDetails[name][]"]').each(function(k,v){
		$this = $(this);
		preFillValues($(this),preFillPets[k])
	})
	}

    $("[name='Booking[number_of_pets]']").change(function () {
        var petCount = parseInt($(this).val());
        var petSingleCount = parseInt($('.pal_single').last().attr('data-id'));
        if (petSingleCount > petCount) {
            remove_pet_singles(petSingleCount, petCount);
        } else if (petSingleCount < petCount) {
            add_pet_singles(petSingleCount, petCount);
        }
    });

// Validation for pet details section
    $("#editProfile-form").submit(function (e) {
        $('.pal_single .help-block-error').remove();
        var isError = false;
        $(".pal_single [name='palDetails[type][]']").each(function (k, v) {
            if ($(this).val() == "") {
                $(this).after($("<p>", {
                    text: 'Pal type cannot be blank.',
                    class: 'help-block help-block-error',
                    style: 'color:#a94442'
                }));
                isError = true;
            }
        });
        
        $(".pal_single [name='palDetails[name][]']").each(function (k, v) {
            if ($(this).val() == "") {
                $(this).after($("<p>", {
                    text: 'Pal Name cannot be blank.',
                    class: 'help-block help-block-error',
                    style: 'color:#a94442'
                }));
                isError = true;
            }
        });

        $(".pal_single [name='palDetails[care_note][]']").each(function (k, v) {
            if ($(this).val() == "") {
                $(this).after($("<p>", {
                    text: 'Care note cannot be blank.',
                    class: 'help-block help-block-error',
                    style: 'color:#a94442'
                }));
                isError = true;
            }
        });

        if (isError) {
            e.preventDefault();
            return false;
        }
    });
    
    // Add autocomplete on pal name input to search user's pets
    palAutoComplete($(".booking_pal_name"));

    $('body').on('click', '._remove_pal', function () {
        var select = $(this).parent().parent().parent().parent().find('[name="palDetails[type][]"]').removeAttr('readonly'); 
        $(this).parent().parent().parent().parent().find('.booking_pal_name').val('').removeAttr('readonly');
        $(this).parent().parent().parent().parent().find('[name="palDetails[care_note][]"]').text('');
        delete  window.selectedPets[$(this).parent().parent().parent().find("[name='palDetails[id][]']").val()];
        $(this).parent().parent().parent().find("[name='palDetails[id][]']").val('');
        var sValue = select.find('option').attr('value');
        select.val(sValue);
        $(this).parent().parent().parent().find("._remove_pal").hide();
    });

});
