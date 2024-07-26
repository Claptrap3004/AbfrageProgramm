const init = () => {
    console.log('');
}

const changeModal = (title, text, confirmFunction) => {
    console.log('modal set')
    let confirmButton = document.querySelector('#modalConfirm');
    let clone = confirmButton.cloneNode(true);
    confirmButton.replaceWith(clone);
    document.querySelector('#modalTitle').innerHTML = title;
    document.querySelector('#modalText').innerHTML = text;
    document.querySelector('#modalConfirm').addEventListener('click', confirmFunction);
}

const removeLeftContentSpacer = () => {
    document.querySelector('#spacerContentLeft').remove();
}