let divRowOfInputImageIllustration = document.querySelector("#divRowOfInputImageIllustration");
let inputFileType = document.querySelector("#article_imageIllustration");

divRowOfInputImageIllustration.addEventListener("click", (event) => {// si la div contenant l'input est clické, considerer que l'input de type file est clické aussi
    inputFileType.click();
})
