let questions

const initSelectQuestions = () => {
    questions = JSON.parse(document.querySelector('#jsDataQuestions').getAttribute('data-content'));
    resizeLeftContentSpacer(10);
    createRadioButtons();
}

const createRadioButtons = () => {
    for (const question of questions) {
        let element = createRadioButton(question)
        document.querySelector('#showQuestions').appendChild(element);
    }
}

const createRadioButton = (question) => {
    let div = document.createElement('div');
    let label = document.createElement('label');
    let input = document.createElement('input');

    label.setAttribute('data-bs-toggle', 'button');
    label.addEventListener('click', clickQuestion)


    div.className = "row align-self-center mx-5 pb-2";
    label.className = "questionLabels col align-self-center bold btn btn-outline-info rounded-3 shadow btn-lg  mx-3 my-2 p-1 w-100";
    input.className = "btn-check";


    input.id = 'q_id' + question.id;
    label.id = 'ql_id' + question.id;
    label.for = 'ql_id' + question.id;

    label.innerHTML = question.text;
    input.type = 'radio';
    input.name = 'questionId';
    input.autocomplete = 'off';
    input.value = question.id;

    div.appendChild(input);
    div.appendChild(label);
    if (question.id === questions[0].id) input.checked = true;
    return div;
}

const clickQuestion = (event) => {
    let idStr = "#q_id" + event.target.id.replace('ql_id', '');
    let button = document.querySelector(idStr);
    button.click();
    let labels = document.querySelectorAll('.questionLabels');
    for (const label of labels) {
        if (label === event.target) continue;
        label.ariaPressed = 'false';
        label.className = "questionLabels col align-self-center bold btn btn-outline-info rounded-3 shadow btn-lg  mx-3 my-2 p-1 w-100";
    }
    let anker = document.querySelector('#selectQuestionToEdit');
    anker.href = 'edit/editQuestion/' + button.value;
}

