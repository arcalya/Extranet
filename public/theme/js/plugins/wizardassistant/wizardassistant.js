/**
 * This tool permits to pass by step by step a Wizard tools
 * 
 * @returns {void}
 */
(function ($) {

    $.fn.wizardassistant = function (options) {
        if(this.length == 0 ){
            return;
        }
        // This is the easiest way to have default options.
        var selector = this;
        var wizardNodeHtml;
        var currentWizardNode;
        var steps, url, step, noStep, idIntervention;
        
        var getSteps = function () {
            steps = selector.attr('data-steps');
            steps = JSON.parse(steps);
            return steps;
        };

        var getUrl = function () {
            url = selector.attr('data-url');
            return url;
        };

        var getIdItervention = function () {
            idIntervention = selector.attr('data-idIntervention');
            return idIntervention;
        };

        var getNoStep = function () {
            var noStep;
            for (i = 0; i < steps.length; i++) {
                if (steps[i].isActive == true) {
                    noStep = i + 1;
                    return noStep;
                }
                ;
            }
        };

        var getCurrentStep = function () {
            for (i = 0; i <= steps.length; i++) {
                if (steps[i].isActive == true) {
                    step = steps[i];
                    return steps[i];
                }
                ;
            }
        };

        var setPreviousStep = function (noPosition) {
            for (i = 1; i < noPosition; i++) {
                $('#wizard ul.wizard_steps>li:nth-child( ' + (i) + ' ) a').addClass('done');
            }
        };

        var setStep = function () {
            var noPosition = noStep;
            $('#wizard ul.wizard_steps>li:nth-child( ' + noPosition + ' ) a').addClass('selected');
            setPreviousStep(noPosition);
        };

        var setNext = function () {

        };


        var getNoStepUri = function () {
            var path = window.location.pathname;
            var pathTable = path.split("/");
            pathTable.shift();
            for (i = 0; i < pathTable.length; i++) {
                if (Number.isInteger(Number(pathTable[i]))) {
                    return pathTable[i];
                }

            }
        };

        var setClickStep = function () {
            $('.wizard_steps li a').click(function () {
                //$('input#step').val(Number( $(this).attr('href') ) - 1);
            });
        };

        var setBtnPreviousStep = function () {
            var step = $('input#step').val();
            if (step > '1') {
                $('#btn-previous').toggleClass('hide');
            }

            $('#btn-previous').click(function () {

                if (step > 1) {
                    $('input#step').val($('input#step').val() - 1);
                }
            });
        };
        var createWizard = function (options, selector) {
            steps = getSteps();
            step = getCurrentStep();
            noStep = getNoStep();
            idIntervention = getIdItervention();
            url = getUrl();
            selector.append('<ul class="wizard_steps"></ul>');
            $.each(steps, function (index, element) {
                var nbrPosition = index + 1;
                wizardNodeHtml = $('<li> <a href=""> <span class="step_no">' + nbrPosition + '</span> <span class="step_descr">' + steps[index].title + ' </span> </a> </li>');
                if (idIntervention == '' || idIntervention == undefined) {
                    $(wizardNodeHtml).find('a').attr('href', "#");
                } else {
                    $(wizardNodeHtml).find('a').attr('href', url + nbrPosition + "/" + idIntervention);
                }
                currentWizardNode = wizardNodeHtml;
                $('.wizard_steps').append(currentWizardNode);
            });
            setStep();
            setBtnPreviousStep();
        };

        createWizard(options, selector);

    };

}(jQuery));


