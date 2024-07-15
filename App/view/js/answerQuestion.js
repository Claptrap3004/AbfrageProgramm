function clickAnswer(id) {
    let idStr = "#a_id" + id;
    document.querySelector(idStr).checked = !document.querySelector(idStr).checked
}

let idString = '';
for (const givenAnswer of givenAnswers) {
    idString = '#l_id' + givenAnswer;
    let element = document.querySelector(idString);
    element.ariaPressed = 'true';
    element.className += ' active';
    console.log()
    clickAnswer(givenAnswer);
}
if (Number({{ contentInfo.actual }}) === 1){
    document.querySelector('#prev').disabled = true;
    document.querySelector('#prev').className =  "btn-lg btn-outline-info shadow-sm";
}
if ({{ contentInfo.totalQuestions }} === {{ contentInfo.actual }}){
    document.querySelector('#next').disabled = true;
    document.querySelector('#next').className =  "btn-lg btn-outline-info shadow-sm";
}