
function setLoginScreen(){
    let newContentLeft = document.createElement('div');
    newContentLeft.className = "col-4 bg-light rounded-5 my-2 py-2 align-self-left scrollable-contentleft";
    document.querySelector('#spacerContentLeft').className = "col-3";
    document.querySelector('#contentRight').hidden = 'hidden';
    document.querySelector('#contentLeft').replaceWith(newContentLeft);
    console.log(document.querySelector('#contentLeft').className);
}


function clickCategory(categoryId) {
    let valueId = "#numberOfQuestions" + categoryId;
    let checkBoxId = "#categorySwitch" + categoryId;
    let maxQuestionsId = "#maxQuestions" + categoryId;
    let val = document.querySelector(checkBoxId).checked ? document.querySelector(maxQuestionsId).value : 0;
    document.querySelector(valueId).innerHTML = val;
    let maxValue = Number(document.querySelector('#maxVal').innerHTML);
    maxValue = val > 0 ? maxValue + Number(document.querySelector(maxQuestionsId).value) :
        maxValue - Number(document.querySelector(maxQuestionsId).value);
    document.querySelector('#customRange').max = maxValue;
    document.querySelector('#currentVal').innerHTML =
        Number(document.querySelector('#currentVal').innerHTML) > maxValue ?
            maxValue : document.querySelector('#currentVal').innerHTML;
    document.querySelector('#maxVal').innerHTML = `${maxValue}`;
    document.querySelector('#maxQuestions').innerHTML = document.querySelector('#maxVal').innerHTML;
}

function changeAllCategories() {
    let isChecked = document.querySelector('#categoryAll').checked;
    document.querySelectorAll('.categories').forEach(function (value) {
        if (value.checked !== isChecked) {
            value.checked = !value.checked;
            clickCategory(value.value)
        }
    });
}

function trackVal(element) {
    element.nextElementSibling.value = element.value;
    document.getElementById('currentVal').innerHTML = element.value;
}

function toggleLoginRegister(toggleButton) {
    let buttonUserName = document.querySelector('#inputUsername');
    let buttonEmailValidate = document.querySelector('#inputEmailValidate');
    let buttonPasswordValidate = document.querySelector('#inputPasswordValidate');
    let labelUserName = document.querySelector('#labelInputUsername');
    let labelEmailValidate = document.querySelector('#labelInputEmailValidate');
    let labelPasswordValidate = document.querySelector('#labelInputPasswordValidate');
    let buttonConfirm = document.querySelector('#confirm');

    if (toggleButton.innerHTML === 'go to Register') {
        buttonUserName.type = 'text';
        buttonEmailValidate.type = 'email';
        buttonPasswordValidate.type = 'password';
        labelUserName.style.visibility = "visible";
        labelEmailValidate.style.visibility = "visible";
        labelPasswordValidate.style.visibility = "visible";
        buttonConfirm.name = 'registerUser';
        buttonConfirm.innerHTML = 'Sign Up';
        toggleButton.innerHTML = 'go to Login';
    } else {
        buttonUserName.type = 'hidden';
        buttonEmailValidate.type = 'hidden';
        buttonPasswordValidate.type = 'hidden';
        labelUserName.style.visibility = "hidden";
        labelEmailValidate.style.visibility = "hidden";
        labelPasswordValidate.style.visibility = "hidden";
        buttonConfirm.name = 'loginUser';
        buttonConfirm.innerHTML = 'Sign In';
        toggleButton.innerHTML = 'go to Register';
    }
}

function showDetails(button, text, explanation, answers) {
    document.querySelector('#details').collapse(false);
    document.querySelector('#detailsQuestion').innerHTML = text;
    document.querySelector('#detailsDescription').innerHTML = explanation;
    let detailsAnswers = document.querySelector('#detailsAnswers');
    detailsAnswers.innerHTML = document.createElement("p").innerHTML = '';
    for (let answersKey in answers) {
        let nextNode = document.createElement("p");
        nextNode.innerHTML = answers[answersKey].text;
        nextNode.style.textAlign = 'center';
        nextNode.style.color = answers[answersKey].isRight === 'true' ? 'green' : 'red';
        nextNode.style.background = answers[answersKey].isSelected === 'true' ? 'yellow' : detailsAnswers.style.background;
        detailsAnswers.appendChild(nextNode);
    }
}

function clearStatsOfQuestion(timesAsked, timesRight){
    let stats = document.querySelector('#clearStatsOfQuestion');
    stats.checked = !stats.checked;
    document.querySelector('#statsTimesAsked').innerHTML = (stats.checked ? 0 : timesAsked) + ' times asked';
    document.querySelector('#statsTimesRight').innerHTML = (stats.checked ? 0 : timesRight) + ' times answered correct';
    document.querySelector('#labelClearStats').innerHTML = stats.checked ? ' undo clear Stats ' : ' clear Stats ';
}