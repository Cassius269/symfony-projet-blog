window.addEventListener("load",()=>{
    console.log("hello");
    // On recupère tous les élements nécessaires
let audio = document.querySelector("audio");
let track = document.querySelector("#track");
let elapsed = document.querySelector("#elapsed");
let trackFullTime = document.querySelector("#track-fullTime");
let buttonPlay = document.querySelector("#buttonPlay");
let divButtons = document.querySelector(".lecteurAudioWithButtons")
//let buttonPause = document.querySelector("#buttonPause");
//let buttonStop = document.querySelector("#buttonStop");
let volume = document.querySelector("#volume");
let volumeValue = document.querySelector("#volume-value");
let iconeButtonPlay = document.querySelector(".iconeButton");

// on récupère la durée du MP3
let audioDuration = audio.duration;

console.log(`durée de la piste musicale : ${audioDuration} secondes`);

// on met le max du range de la track à la taille de la durée de l'audio
track.max = audioDuration;

// Créer une fonction qui récupère les minutes et les secondes de la piste de musique
function buildDuration(duree){
    // Recupérer les minutes
    minutes = duree/60;
    secondes = 0; // Déclarer par défaut les secondes à 0

    if(!Number.isInteger(minutes)){// Dans le cas où la piste a une durée avec des secondes
        // Récuperer les secondes dans la durée
        secondes = (minutes - Math.trunc(minutes))*60;

        // Récuperer les parties entières des unités de temps en minutees et secondes
        secondes = Math.trunc(secondes);
        minutes = Math.trunc(minutes);
    }

    return `${minutes}:${secondes<10 ? "0"+secondes : secondes}`
}

buildDuration(audioDuration);


trackFullTime.textContent = buildDuration(audioDuration);


// Gerer les boutons 

divButtons.addEventListener("click", (event)=>{
    if(event.target.nodeName == "IMG"){
        if(event.target.getAttribute("src") === "/images/play-circle.svg"){
            console.log(audio)

            console.log("c'est le bounton play");

            event.target.setAttribute("src", "/images/media-pause.svg");
            event.target.setAttribute("alt", "icône représentant le bouton pause");
            event.target.parentNode.style.right = "14px";
            audio.play();// jouer la musique
        }else {
            console.log("c'est le bouton pause");
            event.target.setAttribute("src", "/images/play-circle.svg");
            event.target.setAttribute("alt", "icône représentant le bouton play");
            event.target.parentNode.style.right = "28px";
            audio.pause(); // mettre en pause de la musique
        }
    }
})

// Mettre un écouteur d'évenement sur les changements de valeur de la track piste de l'audio au clic sur l'input
track.addEventListener("change",(event)=>{

    // Exprimer la durée écoulée en minutes et secondes
    elapsedTime = event.target.value;
    audio.currentTime = elapsedTime; // modifier en temps réelle la valeur du temps actuelle de l'audio avec l'indicateur du temps passé

    elapsed.textContent = buildDuration(elapsedTime) // utiliser la fonction personnalisée de transformée des secondes en minutes et seconde
console.log("elapsed time",elapsedTime);
console.log(event);

})

// Visualiser le parcours de la piste audio
audio.addEventListener("timeupdate",()=>{
    track.value = audio.currentTime;
    elapsed.textContent = buildDuration(audio.currentTime) // utiliser la fonction personnalisée de transformée des secondes en minutes et seconde

    if(audio.currentTime.toFixed(2) === audioDuration.toFixed(2)){// dans le cas où la piste est finie, remettre le bouton play
    iconeButtonPlay.setAttribute("src", "/images/play-circle.svg");
    iconeButtonPlay.setAttribute("alt", "icône représentant le bouton play");
}
 })

 // Evenement pour changer le volume
 volume.addEventListener("input", ()=>{
    audio.volume = volume.value;

    volumeValue.textContent =Math.round(volume.value*100)+"%";
 })
 })