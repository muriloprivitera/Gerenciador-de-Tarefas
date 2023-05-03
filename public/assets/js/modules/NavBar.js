import { sair } from "../utils.js";
export default class NavBar{
    constructor(dom){
        this.dom = dom;
        this.init();
    }
    async init(){
        await this.loadView();
    }

    async loadView(){

        this.html = this.navMenu();
        this.elementos = {
            nav:this.html.querySelector('.navbar-nav'),
            elementoClickMenu: Array.from(this.html.querySelectorAll('.nav-link')),
        }
        await this.listeners()
    }

    async listeners() {
        const idSair = this.elementos.elementoClickMenu[4].id;
        setTimeout(() => {
            document.getElementById(idSair).addEventListener('click',()=> sair.sair())
        }, 200);
    }

    navMenu(){
        const nav = 
        `
        <nav class="navbar navbar-dark bg-dark fixed-top nav">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="./home.html">Organizador de Tarefas</a>
                <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">STUS</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <form class="d-flex mt-3" role="search">
                            <input class="form-control me-2" type="search" placeholder="Procurar" aria-label="Search">
                            <button class="btn btn-success" type="submit">Procurar</button>
                        </form>
                        <hr>
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3" >
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" id="home" href="./home.html">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="perfil" href="./perfil.html">Perfil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="painel" href="./painel.html">Painel</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="relatorios" href="./relatorios.html">Relatorio de Tarefas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="sair">Sair</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        `;
        const node = new DOMParser().parseFromString(nav,'text/html');
        return node.body.firstChild;
    }

}