const init = () => {
    console.log('init');
}

const changeModal = (title, text, confirmFunction) => {
    document.querySelector('#modalTitle').innerHTML = title;
    document.querySelector('#modalText').innerHTML = text;
    document.querySelector('#modalConfirm').addEventListener('click', confirmFunction)
}


const showDetails = (button, text, explanation, answers) => {

    document.querySelector('#detailsQuestion').innerHTML = text;
    document.querySelector('#detailsDescription').innerHTML = explanation;
    let detailsAnswers = document.querySelector('#detailsAnswers');
    detailsAnswers.innerHTML = document.createElement("p").innerHTML = '';
    for (let answersKey in answers) {
        let nextNode = document.createElement("p");
        nextNode.innerHTML = answers[answersKey].text;
        nextNode.style.textAlign = 'center';
        nextNode.style.color = answers[answersKey].isRight === 'true' ? 'green' : 'red';
        nextNode.style.background = answers[answersKey].isSelected === 'true' ? 'yellow' : detailsAnswers.style.background;
        detailsAnswers.appendChild(nextNode);
    }
}
