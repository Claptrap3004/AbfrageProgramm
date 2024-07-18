let statsData;
const initBeforeFinal = () => {
    statsData = JSON.parse(document.querySelector('#jsStatsData').getAttribute('data-content'));
    console.log(statsData)
    for (let i = 0; i < statsData.length; i++) {
        addBeforeFinalButton(statsData[i].questionId,i)
    }
}

const addBeforeFinalButton = (questionId,index) => {
    console.log(questionId, index)
    let newButton = document.createElement('label');
    let addClass = statsData[index].answers !== [] ? 'btn-success' : 'btn-info';
    newButton.className = 'beforeFinalButton btn-lg shadow m-2 p-2 ' + addClass;
    newButton.value = index;
    newButton.innerHTML = questionId;
    newButton.addEventListener('mouseover',showDetails);
    newButton.addEventListener('mouseleave',hideDetails);
    document.querySelector('#beforeFinalButtons').appendChild(newButton);
}
