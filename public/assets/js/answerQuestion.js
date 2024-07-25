let questionObject;
let contentInfos;

const initAnswerQuestion = () => {
    questionObject = JSON.parse(document.querySelector('#jsDataAnswerQuestion').getAttribute('data-content'));
    contentInfos = JSON.parse(document.querySelector('#jsDataContent').getAttribute('data-content'));
    setContentInfos();
    createQuestionInfos();
    createAnswerButtons();
    setListeners();
    setStats();
    markGivenAnswers();
    setButtonState();
}

const createQuestionInfos = () => {
    let label = document.querySelector('#questionText');
    let input = document.createElement('input');
    input.style.visibility = 'hidden';
    input.name = "questionId";
    input.id = "questionId";
    input.value = questionObject.id;
    label.innerHTML = questionObject.text;
    label.appendChild(input);
}

const createAnswerButtons = () => {
    let answers = questionObject.answers;
    for (const answer of answers) {
        let div = document.createElement('div');
        div.className = "row align-self-center mx-5 pb-2";
        let label = document.createElement('label');
        label.className = "answerLabels col align-self-center bold btn btn-outline-secondary rounded-3 shadow btn-lg  mx-2 my-2 p-2 w-100";
        label.id = 'l_id' + answer.id;
        label.innerHTML = answer.text;
        label.setAttribute('data-bs-toggle', 'button');
        let input = document.createElement('input');
        input.type = 'checkbox';
        input.name = 'answers[]';
        input.id = 'a_id' + answer.id;
        input.value = answer.id;
        input.style.visibility = 'hidden';
        label.appendChild(input);
        div.appendChild(label);
        document.querySelector('#answersList').appendChild(div);
    }
}

const setContentInfos = (clear = null) => {
    document.querySelector('#subTitle').innerHTML = `Frage ${contentInfos.actual} / ${contentInfos.totalQuestions}`;
}

const setStats = (clear = null) => {
    let timesAsked = document.querySelector('#statsTimesAsked');
    let timesRight = document.querySelector('#statsTimesRight');
    if (clear === null) {
        document.querySelector('#statsTitle').innerHTML = questionObject.id;
        timesAsked.innerHTML = questionObject.stats.timesAsked;
        timesRight.innerHTML = questionObject.stats.timesRight;
    } else {
        timesAsked.innerHTML = '0';
        timesRight.innerHTML = '0';
    }

}

const setListeners = () => {
    let answerLabels = document.querySelectorAll('.answerLabels');
    for (const answerLabel of answerLabels) {
        answerLabel.addEventListener('click', clickAnswer)
    }
    document.querySelector('#labelClearStatsOfQuestion').addEventListener('click', clearStatsOfQuestion);

}

const markGivenAnswers = () => {
    const givenAnswers = questionObject.givenAnswers;
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
    if (Number(contentInfos.actual) === 1) {
        document.querySelector('#prev').disabled = true;
        document.querySelector('#prev').className = "btn-lg btn-outline-info shadow-sm";
    }
    if (Number(contentInfos.actual) === Number(contentInfos.totalQuestions)) {
        document.querySelector('#next').disabled = true;
        document.querySelector('#next').className = "btn-lg btn-outline-info shadow-sm";
    }

}

const clearStatsOfQuestion = () => {
    console.log('clicked button');
    changeModal('Lösche Stats der aktuellen Frage','Durch bestätigen werden alle Stats zu dieser Frage gelöscht', confirmDeleteStatsOfQuestion)
}



const confirmDeleteStatsOfQuestion = () => {
    console.log('clicked');
    document.querySelector('#statsTimesAsked').innerHTML = '0';
    document.querySelector('#statsTimesRight').innerHTML = '0';
    document.querySelector('#closeModal').click();
    deleteStats();
}

const deleteStats = () => {
    let id = document.querySelector('#questionId').value;
    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/quizQuestion/deleteStatsQuestion",true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`id=${id}`);
}

const clickAnswer = (event) => {
    let id = event.target.id.replace('l_id', '');
    let idStr = "#a_id" + id;
    document.querySelector(idStr).checked = !document.querySelector(idStr).checked
}
