let statsData;
const initBeforeFinal = () => {
    statsData = JSON.parse(document.querySelector('#jsBeforeFinalData').getAttribute('data-content'));
    console.log(statsData)
    for (let i = 0; i < statsData.length; i++) {
        addBeforeFinalButton(statsData[i].questionId,i)
    }
}

const addBeforeFinalButton = (questionId,index) => {
    console.log(questionId, index)
    let newButton = document.createElement('label');
    let addClass = checkAnswersGiven(index) ? 'btn-success' : 'btn-info';
    newButton.className = 'beforeFinalButton btn-lg shadow m-2 p-2 ' + addClass;
    newButton.value = index;
    newButton.innerHTML = questionId;
    document.querySelector('#beforeFinalButtons').appendChild(newButton);
}

const checkAnswersGiven = (index) => {
    let answerGiven = false;
    for (const answer of statsData[index].answers) {
        if (answer.isSelected === 'true') answerGiven = true;
    }
    return answerGiven;
}