function initAnswerQuestion() {
    const givenAnswers = {{ answers }};
    const timesAsked = {{ question.stats.timesAsked }};
    const timesRight = {{ question.stats.timesRight }};
    console.log(answers, timesAsked, timesRight);

    let answerLabels = document.querySelectorAll('.answerLabels');
    for (const answerLabel of answerLabels) {
        answerLabel.addEventListener('click',clickAnswer)
    }
    document.querySelector('#labelClearStatsOfQuestion').addEventListener('click', clearStatsOfQuestion)
    for (const givenAnswer of givenAnswers) {
        let labelId = '#l_id' + givenAnswer;
        let element = document.querySelector(labelId);
        let buttonId = "#a_id" + givenAnswer;
        element.ariaPressed = 'true';
        element.className += ' active';
        document.querySelector(buttonId).checked = !document.querySelector(buttonId).checked
    }

    if (Number({{ contentInfo.actual }}) === 1){
        document.querySelector('#prev').disabled = true;
        document.querySelector('#prev').className =  "btn-lg btn-outline-info shadow-sm";
    }
    if ({{ contentInfo.totalQuestions }} === {{ contentInfo.actual }}){
        document.querySelector('#next').disabled = true;
        document.querySelector('#next').className =  "btn-lg btn-outline-info shadow-sm";
    }

}


const clearStatsOfQuestion = () =>{
    let stats = document.querySelector('#clearStatsOfQuestion');
    stats.checked = !stats.checked;
    document.querySelector('#statsTimesAsked').innerHTML = (stats.checked ? 0 : timesAsked) + ' times asked';
    document.querySelector('#statsTimesRight').innerHTML = (stats.checked ? 0 : timesRight) + ' times answered correct';
    document.querySelector('#labelClearStats').innerHTML = stats.checked ? ' undo clear Stats ' : ' clear Stats ';
}

const clickAnswer = (event)=> {
    let id = event.target.id.replace('l_id','');
    let idStr = "#a_id" + id;
    document.querySelector(idStr).checked = !document.querySelector(idStr).checked
}
