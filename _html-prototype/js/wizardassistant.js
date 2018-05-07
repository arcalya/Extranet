/**
 * This tool permits to pass by step by step a Wizard tools
 * 
 * @returns {void}
 */
(function ($) {

    $.fn.wizardassistant = function (options) {
        // This is the easiest way to have default options.
        var selector = this;

        var createWizard = function (options, selector) {
            var wizardNodeHtml
            var currentWizardNode;
            selector.append('<ul class="wizard_steps"></ul>');
            $('#steps-forms .step-form').each(function( index, element ){
                index = index + 1 ;
                wizardNodeHtml = $('<li> <a href=""> <span class="step_no">{{nbrNodes}}</span> <span class="step_descr">Définir le bureau<br /><small>A qui sera adresser la demande</small> </span> </a> </li>');
                $(wizardNodeHtml).find('a').attr('href', "#step-" + index);
                $(wizardNodeHtml).find('.step_no').text(index);
                $(wizardNodeHtml).find('.step_descr').text($(element).find('header').text());
                currentWizardNode = wizardNodeHtml;
                $('.wizard_steps').append(currentWizardNode);
            });
        };
        createWizard(options, selector);

    };

}(jQuery));

var wizardAssistant = function ()
{
    var step = getWizardCurrentStep();
    var previousStep;

    $('#wizard ul.wizard_steps>li:nth-child( n + 2 ) a').addClass('disabled');

    wizardAssistantSetStep(step);

    $('#wizard ul.wizard_steps>li').on('click', function (e)
    {
        if (isProcessFinished()) {
            e.preventDefault();
            var currentStep = $(this).find('.step_no').text();

            if (($(this).find('a').attr('class') === 'done') && hasWizardStepRight(currentStep)) {
                step = currentStep;
                wizardAssistantSetStep(step, previousStep);
            }
            previousStep = step;
        }
    });


    $('#wizard #wizard-action #btn-previous').on('click', function (e)
    {
        if (step > getWizardFirstStep()) {
            e.preventDefault();
            step--;
            wizardAssistantSetStep(step);
        }
    });

    $('#wizard #wizard-action #btn-modify').on('click', function (e)
    {
        if (step >= getWizardFirstStep()) {
            $('#btn-finish').removeClass('hide');
            $('#btn-modify').addClass('hide');
        }
    });

    $('#modal-btn-finish').on('click', function (e)
    {
        previousStep = step;
        step = Number(getWizardLastStep()) + 1;
        wizardAssistantSetStep(step, previousStep);
        setWizardFinish();
    });

    $('#wizard #wizard-action #btn-next').on('click', function (e)
    {

        e.preventDefault();
        step++;
        wizardAssistantSetStep(step);

        ;
    });
};
/**
 * This method is specificaly called by the "wizardAssistant" method
 * (see comments of "dropdownFilter" method for more information).
 * It defines wich HTML element to show up or hide depending on the step
 * wich is indicated as a parameter 
 * 
 * @param {int} step
 * @returns {void}
 */
var wizardAssistantSetStep = function (step, previousStep)
{
    if (previousStep === undefined) {
        previousStep = step - 1;
    }

    $('#wizard section>div').hide();
    $('#wizard section>div:nth-child( ' + step + ' )').show();
    $('#wizard ul.wizard_steps>li:nth-child( ' + step + ' ) a').removeClass('disabled');
    $('#wizard ul.wizard_steps>li:nth-child( ' + step + ' ) a').addClass('selected');
    $('#wizard ul.wizard_steps>li:nth-child( ' + (previousStep) + ' ) a').removeClass('selected');
    $('#wizard ul.wizard_steps>li:nth-child( ' + (previousStep) + ' ) a').addClass('done');
    wizardDisableButtons();
};


var isOkCheckingForm = function (currentForm) {

    return true;
};

var getWizardCurrentStep = function () {
    var currentStep = $('#wizard ul.wizard_steps>li a.selected span.step_no').text();

    if (currentStep === "") {
        currentStep = 1;
    }
    if (currentStep.length > 1) {
        currentStep = currentStep.substring(0, 1);
    }
    return currentStep;
};

var getWizardLastStep = function () {
    var step = $('#wizard ul.wizard_steps>li').find('.step_no').last().text();
    return step;
};

var getWizardFirstStep = function () {
    var step = $('#wizard ul.wizard_steps>li').find('.step_no').first().text();
    return step;
};

var wizardDisableButtons = function () {
    if (isProcessFinished()) {
        $('#btn-next').addClass('hide');
        $('#btn-finish').addClass('hide');
        $('#btn-modify').removeClass('hide');
    }
    else if (getWizardCurrentStep() == getWizardFirstStep()) {
        $('#btn-previous').addClass('hide');
    }
    else if (getWizardCurrentStep() == getWizardLastStep()) {
        $('#btn-next').addClass('hide');
        $('#btn-finish').removeClass('hide');
    }
    else {
        $('#btn-previous').removeClass('hide');
        $('#btn-next').removeClass('hide');
        $('#btn-finish').addClass('hide');
    }
};

var wizardFormDisabledStep = function (step) {
    if (step === undefined) {
        $('#steps-forms').find('input').prop("disabled", true);
    } else {
        $('#steps-forms').find('#step-' + 1 + ' input').prop("disabled", true);
    }
};

var wizardFormEnableStep = function (step) {
    if (step !== '') {
        $('#steps-forms').find('#step-' + 1 + ' input').prop("disabled", true);
    }
};

var setWizardFinish = function () {
    $('#step-finished').attr('progress', 'finished');
};

var isProcessFinished = function () {
    var finished = false;
    if ($('#step-finished').attr('progress') == 'finished') {
        finished = true;
        return finished;
    }
    ;
};


var hasWizardStepRight = function (step) {
    //TODO récupérer le groupe dans la base de données. 
    return true;
};