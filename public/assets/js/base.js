const init = () => {
    console.log('init');
}

const changeModal = (title, text, confirmFunction) => {
    document.querySelector('#modalTitle').innerHTML = title;
    document.querySelector('#modalText').innerHTML = text;
    document.querySelector('#modalConfirm').addEventListener('click', confirmFunction)
}


