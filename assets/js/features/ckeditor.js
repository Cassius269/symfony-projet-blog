// Création d'une fonction de mise à disposition de l'éditeur CK Editor

// Exportation de la fonction editWithCKEditor dès sa création

export function editWithCKEditor(typeOfEditor, IDinputHiddenContent ){
    // On crée CK Editor sur la balise <textarea>  ayant l'attribut #editor 

    typeOfEditor. create( document.querySelector( '#editor' ),  
//{
//     //  toolbar:['heading','|','bold','italic','|','bulletedList','link'],
//       heading:{
//           options:[
//               {
//                   model:'paragraph',
//                   title: 'Paragraphe',
//                   class: 'ck-editor_paragraphe'
//               },
//               {
//                   model:'heading2',
//                   title: 'Sous titre principal',
//                   view: {
//                       name: 'h2',
//                       classes: 'ck-editor_heading2'
//                   },
//               },
//               {
//                   model:'heading3',
//                   title: 'Sous-titre secondaire',
//                   view: {
//                       name: 'h3',
//                       classes: 'ck-editor_heading3'
//                   }
//               },
//           ]
//       },
//     },
     { 
        // Les identifiants de services
   cloudServices: { 
       tokenUrl: 'https://120090.cke-cs.com/token/dev/5sNVm4KFV2rj9PQAXPl3OJflVp6pgIhZPS8i?limit=10 ', 
       uploadUrl: 'https://120090.cke-cs.com/easyimage/upload/' 
   }
   }) 
   .then(editor => { 
       let inputHiddenContent = document.querySelector(IDinputHiddenContent); 

       if(inputHiddenContent.value){// Dans le cas où l'éditeur se trouve dans une situation de mise à jour, c-à-d input hidden avec valeur préremplie
        editor.setData(inputHiddenContent.value); // Préremplir l'éditeur du contenu présent en base de données
       }

       let form = document.querySelector("form"); 
       form.addEventListener('submit', (event) => { 
       event.preventDefault(); 
       inputHiddenContent.value = editor.getData(); 
       form.submit(); 
   }); 
   }) 
   .catch(error => { 
       console.error(error); 
   
   });  
}