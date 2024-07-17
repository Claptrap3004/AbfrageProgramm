const initAnswerQuestion = () => {
    setListeners();
    markGivenAnswers();
    setButtonState();
}

const setListeners = () => {
    let answerLabels = document.querySelectorAll('.answerLabels');
    for (const answerLabel of answerLabels) {
        answerLabel.addEventListener('click',clickAnswer)
    }
    document.querySelector('#labelClearStatsOfQuestion').addEventListener('click', clearStatsOfQuestion)

}

const markGivenAnswers = () => {
    const givenAnswers = JSON.parse(document.querySelector('#jsDataAnswers').getAttribute('data-content'));
    for (const givenAnswer of givenAnswers) {
        let labelId = '#l_id' + givenAnswer;
        let element = document.querySelector(labelId);
        let buttonId = "#a_id" + givenAnswer;
        element.ariaPressed = 'true';
        element.className += ' active';
        document.querySelector(buttonId).checked = !document.querySelector(buttonId).checked
    }

}

const setButtonState = () => {
    const actual = document.querySelector('#jsDataActual').getAttribute('data-content');
    const numberOfQuestions = document.querySelector('#jsDataNumberOfQuestions').getAttribute('data-content');
    if (Number(actual) === 1) {
        document.querySelector('#prev').disabled = true;
        document.querySelector('#prev').className = "btn-lg btn-outline-info shadow-sm";
    }
    if (Number(actual) === Number(numberOfQuestions)) {
        document.querySelector('#next').disabled = true;
        document.querySelector('#next').className = "btn-lg btn-outline-info shadow-sm";
    }

}


const clearStatsOfQuestion = () =>{
    const questionStats = JSON.parse(document.querySelector('#jsDataQuestionStats').getAttribute('data-content'));

    let stats = document.querySelector('#clearStatsOfQuestion');
    stats.checked = !stats.checked;
    document.querySelector('#statsTimesAsked').innerHTML = (stats.checked ? 0 : questionStats['timesAsked']) + ' times asked';
    document.querySelector('#statsTimesRight').innerHTML = (stats.checked ? 0 : questionStats['timesRight']) + ' times answered correct';
    document.querySelector('#labelClearStats').innerHTML = stats.checked ? ' undo clear Stats ' : ' clear Stats ';
}

const clickAnswer = (event)=> {
    let id = event.target.id.replace('l_id','');
    let idStr = "#a_id" + id;
    document.querySelector(idStr).checked = !document.querySelector(idStr).checked
}
