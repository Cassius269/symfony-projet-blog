// Importation du module ckEditor
import { editWithCKEditor } from '../features/ckeditor';

// Importation du module clickOnInputFileType
import { clickOnInputFileType } from '../features/clickOnInputFileType';

// // On crée CK Editor sur la balise <textarea>  ayant l'attribut #editor 
editWithCKEditor(ClassicEditor, '#article_content'); // l'élement à l'ID "#article_content" représente l'input caché qui va être rempli du contenu entré dans l'éditeur

// On sélectionne un fichier pour l'image d'illustration
clickOnInputFileType("#divRowOfInputImageIllustration","#article_mainImageIllustration_imageFile_file");