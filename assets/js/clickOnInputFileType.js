let divRowOfInputImageIllustration = document.querySelector("#divRowOfInputImageIllustration");
let inputFileType = document.querySelector("#article_mainImageIllustration_imageFile_file");
divRowOfInputImageIllustration.appendChild(inputFileType);

divRowOfInputImageIllustration.addEventListener("click", (event) => {// si la div contenant l'input est clické, considerer que l'input de type file est clické aussi
    event.stopPropagation() // Empêcher la propogation de l'évenement
    inputFileType.click();

})

/* Afficher le nom du fichier uploadé */
if (inputFileType) { // Si l'input existe
    inputFileType.addEventListener('change', (event) => {// Si la valeur de l'input change, c-à-d quand un fichier est chargé
        let files = event.target.files;
        if (files.length > 0) {
            let file = files[0];
            let extension = file.type;
            let paragrapheFilename = document.querySelector("#filename");

            console.log(file); // Affiche le premier fichier sélectionné

            //inputFileType.insertAdjacentHTML("af")
            paragrapheFilename.textContent = file.name; // Remplir le contenu de la paragraphe par le nom du fichier
        } else {
            console.log("Aucun fichier sélectionné");
        }
    });

    // Créer un gestionnaire d'évenement qui écoute l'input de type File au click programatique de la DIV parente
    inputFileType.addEventListener("click", (e) => {
        e.stopPropagation(); // Stopper le bouillonnement de l'évenement vers le parent si la DIV globale contenant l'input et l'icone d'image est cliqué
    })
} else {
    console.log("L'élément input file n'existe pas");
}


/* Le drag and drop de fichiers images */

divRowOfInputImageIllustration.addEventListener('dragover',(e) => {
    console.log(e.target)
})