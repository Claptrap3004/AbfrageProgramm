const initWelcome = ()=>{
    document.querySelector('#clearAllStatsButton').addEventListener('click',clearAllStats)
}
const clearAllStats = () => {
    changeModal('Löschen aller Stats','Durch bestätigen werden alle Stats gelöscht', confirmDeleteStats)

}

const confirmDeleteStats = () => {
    document.querySelector('#allTimesAsked').innerHTML = '0';
    document.querySelector('#allTimesRight').innerHTML = '0';
    document.querySelector('#allRate').innerHTML = '0';
}