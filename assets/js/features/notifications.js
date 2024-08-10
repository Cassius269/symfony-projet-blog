

window.addEventListener('load', function () {
    // Sélection des éléments
    let iconeNotif = document.querySelector("#iconeNotif"); // icone de notification
    let listNotifications = document.querySelector("#listNotifications"); // balise ul de la liste des notification
    // let numberOfNotifcations = document.querySelector("#numberOfNotifcations");
    // let divNotification = document.querySelector("#iconeRingNotifications");

    // Mise en place d'un gestionnaire d'évenement de type click sur l'icone de notification pour montrer/cacher les notifications
    iconeNotif.addEventListener("click",function (event) {
        listNotifications.classList.toggle("clickAndShow");
    })
    })