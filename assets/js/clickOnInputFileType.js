let divRowOfInputImageIllustration = document.querySelector("#divRowOfInputImageIllustration");
let inputFileType = document.querySelector("#article_imageIllustration");

divRowOfInputImageIllustration.addEventListener("click", (event) => {// si la div contenant l'input est clické, considerer que l'input de type file est clické aussi
    event.stopPropagation() // Empêcher la propogation de l'évenement
    inputFileType.click();

})



/* Le drag and drop de fichiers images */

divRowOfInputImageIllustration.addEventListener('dragover',(e) => {
    console.log(e.target)
})