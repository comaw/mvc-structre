/**
 * powered by php-shaman
 *  26.08.2016
 * beejee
 */
$(document).ready(function() {

    var formValidate = {

        formField: ['name', 'email', 'text'],
        validateOnSubmit: function () {
            var error = 0;
            var formField = this.formField;
            jQuery.each(formField, function (key, value) {
                if(!formValidate.validateFieldSubmit('#' + value)){
                    error++;
                }
            });
            if(error > 0){
                return false;
            }
            return true;
        },
        validateFieldSubmit: function (field) {
            var value = jQuery(field).val();
            if(field.id == 'email'){
                value = this.isEmail(value);
            }
            var dom = jQuery(field).parent('div');
            if (value === undefined || !value) {
                dom.removeClass('has-success');
                dom.addClass('has-error');
                return false;
            } else {
                dom.removeClass('has-error');
                dom.addClass('has-success');
                return true;
            }
        },
        validate: function (FormError) {
            jQuery.each(FormError, function (key, value) {
                var dom = jQuery('#' + key).parent('div');
                if (value) {
                    dom.removeClass('has-success');
                    dom.addClass('has-error');
                } else {
                    dom.removeClass('has-error');
                    dom.addClass('has-success');
                }
            });
        },
        validateField: function (field) {
            var value = jQuery(field).val();
            if(field.id == 'email'){
                value = this.isEmail(value);
            }
            var dom = jQuery(field).parent('div');
            if (!value) {
                dom.removeClass('has-success');
                dom.addClass('has-error');
            } else {
                dom.removeClass('has-error');
                dom.addClass('has-success');
            }
        },
        isEmail: function (email) {
            var regex = /^[a-z0-9_-]+@[a-z0-9-]+\.([a-z]{1,6}\.)?[a-z]{1,6}$/i;
            return regex.test(email);
        }
    };

    jQuery('#form-reviews').submit(function () {
        return formValidate.validateOnSubmit();
    });

    jQuery('#name, #email, #text').change(function () {
        formValidate.validateField(this);
    });

    if (window.FormError !== undefined) {
        formValidate.validate(FormError);
    }

});