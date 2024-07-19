let editData;
let categories;
let answers;

const initEditQuestion = () => {
    editData = JSON.parse(document.querySelector('#jsDataEditQuestions').getAttribute('data-content'));
    categories = JSON.parse(document.querySelector('#jsDataEditCategories').getAttribute('data-content'));
    answers = [...editData.rightAnswers,...editData.wrongAnswers];
    console.log(editData);
    console.log(categories);
    console.log(answers);
    for (let i = answers.length; i < 4; i++) {
        answers.push({id:null,text:''})
    }
    createSelectOptions();
    createInputFields();
    fillInputFields();
}

const createSelectOptions = () => {
    for (const category of categories) {
        let categorySelect = document.createElement('option');
        if (category.id === editData.category.id) categorySelect.selected = true;
        categorySelect.value = category.id;
        categorySelect.innerHTML = category.text;
        document.querySelector('#editCategory').appendChild(categorySelect);
    }
}


const createInputFields = () =>{
    for (let i = 0; i < answers.length; i++) {
        createAnswerInputField(i)
    }
}

const createAnswerInputField = (index) => {
    let div = document.createElement('div');
    let label = document.createElement('label');
    let checkbox = document.createElement('input');
    let field = document.createElement('input');

    checkbox.name = 'answerCheckBox';
    field.name = "answerText";

    div.id = "answerDiv" + index;
    checkbox.id = checkbox.name + index;
    field.id = field.name + index;

    div.className = 'editDiv';
    checkbox.className = 'editCheckBox'
    field.className = "editText";

    checkbox.type = "checkbox";
    field.type = "text";

    label.appendChild(field);
    div.appendChild(checkbox);
    div.appendChild(label);

    document.querySelector('#editAnswersDiv').appendChild(div);
}

const fillInputFields = () =>{
    fillQuestionField();
    for (let i = 0; i < answers.length; i++) {
        fillAnswerField(i);
    }
}

const fillQuestionField = () => {
    document.querySelector('#editQuestionId').value = editData.id;
    document.querySelector('#editQuestionText').value = editData.text;
    document.querySelector('#editQuestionExplanation').value = editData.explanation;
}

const fillAnswerField = (index) => {

}