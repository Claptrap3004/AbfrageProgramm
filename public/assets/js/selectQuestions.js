let categories;
let all;
let selectedAll;

const initSelectQuestions = () => {
    categories = JSON.parse(document.querySelector('#jsDataCategories').getAttribute('data-content'));
    setAll();
    setListeners();
    initFields();
}

const setAll = () => {
    all = 0;
    for (const category of categories) {
        all += Number(category.number);
    }
    selectedAll = all;
}

const setSelected = () => {
    selectedAll = 0
    for (const selectedElement of document.querySelectorAll('.categories')) {
        let maxQuestionsId = "#maxQuestions" + selectedElement.value;
        selectedAll += selectedElement.checked ? Number(document.querySelector(maxQuestionsId).value) : 0;
    }
    setMax();
}

const initFields = () => {
    document.querySelector('#maxQuestionsAll').innerHTML = all;
    document.querySelector('#currentVal').value = selectedAll > 25 ? 25 : 0;
    setMax();
}

const setMax = () => {
    document.querySelector('#maxQuestionsSelected').innerHTML = selectedAll;
    let current =  document.querySelector('#currentVal');
    current.value = Number(current.value) < selectedAll ? Number(current.value) :
        selectedAll > 25 ? 25 : 0;
}

const setListeners = () => {
    document.querySelector('#categoryAll').addEventListener('click', changeAllCategories);
    const categoryCheckboxes = document.querySelectorAll('.categories');
    for (const categoryCheckbox of categoryCheckboxes) {
        categoryCheckbox.addEventListener('click', clickCategory);
    }
    document.querySelector('#currentVal').addEventListener('change',checkCurrent);
}


const clickCategory = (event) => {
    const categoryId = event.target.value;
    console.log(categoryId);
    let valueId = "#numberOfQuestions" + categoryId;
    let checkBoxId = "#categorySwitch" + categoryId;
    let maxQuestionsId = "#maxQuestions" + categoryId;
    document.querySelector(valueId).innerHTML = document.querySelector(checkBoxId).checked ? document.querySelector(maxQuestionsId).value : 0;
    setSelected();
}

const changeAllCategories = () => {
    let isChecked = document.querySelector('#categoryAll').checked;
    for (let category of document.querySelectorAll('.categories')) {
            if (category.checked !== isChecked) category.click();
    }
}

const checkCurrent = () => {
    let current = document.querySelector('#currentVal');
    if (Number(current.value) > selectedAll) current.value = selectedAll;
    else if (Number(current.value) < 0) current.value = 0;
}
