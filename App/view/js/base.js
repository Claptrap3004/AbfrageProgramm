function clickFn(id) {
    let idStr = "#a_id" + id;
    console.log(idStr)
    // let checkbox = document.querySelector(idStr).checked;
    // document.querySelector(idStr).checked = !checkbox.checked;
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