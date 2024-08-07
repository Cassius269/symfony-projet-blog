window.addEventListener("load",()=> {
    let main = document.querySelector("main");
    console.log(main);

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

    return `${minutes}:${secondes<10 ? "0"+secondes : secondes}`; 
    }


    // Fonction pour mettre en pause toutes les pistes d'abord avant de lancer/reprendre une piste audio
    function setPauseAllEpisodes() {
        let episodes = document.querySelectorAll("audio");
        episodes.forEach((element) => {
            element.pause();
            element.nextElementSibling.firstElementChild.src = "/images/play-circle.svg";     
        })
        console.log(episodes);
    }
    // Mise en place de la délégation évenementielle
    main.addEventListener("click", (event)=> {
        if(event.target.nodeName == "IMG"){
            if(event.target.getAttribute("src") === "/images/play-circle.svg"){  
                setPauseAllEpisodes();
                console.log("button play");
                console.log(event.target.parentNode.previousElementSibling);// Affichage de la balise audio de l'icone play appuyé
                event.target.src = "/images/media-pause.svg";
                event.target.parentNode.previousElementSibling.play();// Cibler la balise audio et faire play()
        }else if (event.target.getAttribute("src") === "/images/media-pause.svg"){ 
                 event.target.parentNode.previousElementSibling.pause(); // mettre en pause le podcast en ciblant la balise audio avec la méthode pause()
                 event.target.src = "/images/play-circle.svg";            
                 console.log("bouton pause");
        }
        }
    }
     )

    // Délégation évenementielle sur le main pour modifier en temps réél la durée courante de la piste audio au clic de l'input range contenant la piste audio

    main.addEventListener("change", (event)=> {
        console.log(event.target);
        if(event.target.classList.contains("track")){// Si c'est un input ayant la class "track" (piste de progression de l'épisode)

        // Exprimer la durée écoulée en minutes et secondes
        elapsedTime = event.target.value; 
        inputTrack = event.target.parentNode.nextElementSibling.firstElementChild;
        spanElapsedTime = event.target.previousElementSibling.firstElementChild;
        console.log("span elapsed time",elapsedTime);
        console.log(spanElapsedTime);
        event.target.parentNode.previousElementSibling.firstElementChild.currentTime = elapsedTime; // modifier en temps réelle la valeur du temps actuelle de l'audio avec l'indicateur du temps passé

        spanElapsedTime.textContent = buildDuration(elapsedTime) // utiliser la fonction personnalisée de transformer des secondes en minutes et seconde
        console.log("elapsed time",elapsedTime);
        console.log(event);
        }

    })

    // Boucle de toutes les pistes audio et ecoute de l'évenement "timeupdate" pour mettre à jour le passé
    let audios = document.querySelectorAll("audio");
    audios.forEach((element) => {
        console.log(element)
        element.addEventListener("timeupdate", () => {
            let track = element.parentNode.nextElementSibling.firstElementChild.nextElementSibling;
            let spanElapsedTime = element.parentNode.nextElementSibling.firstElementChild.firstElementChild;
           
            track.value = element.currentTime; // modifier la valeur de l'input track pour qu'il correspond à la durée en cours
            spanElapsedTime.textContent = buildDuration(element.currentTime) // utiliser la fonction personnalisée de transformée des secondes en minutes et seconde pour l'affichage du temps écoulé
            if(element.currentTime.toFixed(2) === element.duration.toFixed(2)){// dans le cas où la piste est finie, remettre le bouton play
            iconeButtonPlay.setAttribute("src", "assets/images/play-circle.svg");
            iconeButtonPlay.setAttribute("alt", "icône représentant le bouton play");
            }
        })
           
        }
)})