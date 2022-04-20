class DW_Controller
{
    constructor() {
        console.log(document.body, 'constructor')
        // A ce stade-ci, le DOM n'est pas encore prêt car on est dans le <head>
        // Permet d'instancier les classes utilitaires par exemple

    }

    run() {
        // Désormais, le DOM est prêt, on peut commencer à manipuler
        // Permet d'instancier les classes de composants d'interface par exemple
        console.log(document.body, 'OK')
        // ici : this.responsiveMenu = new ResponsiveMenu()
    }

}
window.dw = new DW_Controller()
window.addEventListener('load', () => {
    window.dw.run()
})