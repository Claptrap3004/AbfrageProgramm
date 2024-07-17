const init = () => {
    console.log('init');
}

const changeModal = (title, text, confirmFunction) => {
    document.querySelector('#modalTitle').innerHTML = title;
    document.querySelector('#modalText').innerHTML = text;
    document.querySelector('#modalConfirm').addEventListener('click', confirmFunction)
}







const clickCategory = (categoryId) => {
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

const changeAllCategories = () => {
    let isChecked = document.querySelector('#categoryAll').checked;
    document.querySelectorAll('.categories').forEach(function (value) {
        if (value.checked !== isChecked) {
            value.checked = !value.checked;
            clickCategory(value.value)
        }
    });
}

const trackVal = (element) => {
    element.nextElementSibling.value = element.value;
    document.getElementById('currentVal').innerHTML = element.value;
}


const showDetails = (button, text, explanation, answers) => {

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

const clearStatsOfQuestion = (timesAsked, timesRight) =>{
    let stats = document.querySelector('#clearStatsOfQuestion');
    stats.checked = !stats.checked;
    document.querySelector('#statsTimesAsked').innerHTML = (stats.checked ? 0 : timesAsked) + ' times asked';
    document.querySelector('#statsTimesRight').innerHTML = (stats.checked ? 0 : timesRight) + ' times answered correct';
    document.querySelector('#labelClearStats').innerHTML = stats.checked ? ' undo clear Stats ' : ' clear Stats ';
}