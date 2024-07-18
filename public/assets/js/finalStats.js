let statsData;
const initFinalStats = () => {
    statsData = JSON.parse(document.querySelector('#jsStatsData').getAttribute('data-content'));
    console.log(statsData)
    for (let i = 0; i < statsData.length; i++) {
        addStatsButton(statsData[i].questionId,i)
    }
}
const addStatsButton = (questionId,index) => {
    console.log(questionId, index)
    let newButton = document.createElement('label');
    newButton.style.maxHeight = '100%';
    newButton.style.minHeight = '100%';
    newButton.style.maxWidth = '18%';
    newButton.style.minWidth = '18%';
    let addClass = Number(statsData[index].isCorrect) ? 'btn-success' : 'btn-danger';
    newButton.className = 'statsButton btn-lg shadow m-2 p-2 ' + addClass;
    newButton.value = index;
    newButton.innerHTML = questionId;
    newButton.addEventListener('mouseover',showDetails);
    newButton.addEventListener('mouseleave',hideDetails);
    document.querySelector('#statsButtons').appendChild(newButton);

}

const showDetails = (event) => {
    let question = statsData[Number(event.target.value)];
    document.querySelector('#details').removeAttribute('hidden');
    document.querySelector('#detailsQuestion').innerHTML = question.text;
    document.querySelector('#detailsDescription').innerHTML = question.explanation;
    let detailsAnswers = document.querySelector('#detailsAnswers');
    detailsAnswers.innerHTML = document.createElement("p").innerHTML = '';
    for (let answersKey in question.answers) {
        let nextNode = document.createElement("p");
        nextNode.innerHTML = question.answers[answersKey].text;
        nextNode.style.textAlign = 'center';
        nextNode.style.color = question.answers[answersKey].isRight === 'true' ? 'green' : 'red';
        nextNode.style.background = question.answers[answersKey].isSelected === 'true' ? 'yellow' : detailsAnswers.style.background;
        detailsAnswers.appendChild(nextNode);
    }
}
const hideDetails = (event) => {
    document.querySelector('#details').hidden = 'hidden';

}
