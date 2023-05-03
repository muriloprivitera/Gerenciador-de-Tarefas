import NavBar from './NavBar.js';
import { funcoesCookie } from '../utils.js';

class Relatorios{
    constructor(){
        this.SideBar = new NavBar(document);
        this.init();
    }
    async init(){
        await this.loadView();
    }

    async loadView(){
        this.divs = {
            menu:document.getElementById('nav-menu'),
        }
        await this.listeners()
    }

    async listeners() {
      document.addEventListener('DOMContentLoaded', () => this.divs.menu.append(this.SideBar.navMenu()))
      document.addEventListener('DOMContentLoaded', () => document.getElementById('relatorios').classList.add('active'))
      document.addEventListener('DOMContentLoaded', () => funcoesCookie.validaCookieUsuario())
    }
}
(() => {
    try {
      if (!window.Relatorios) {
        window.Relatorios = new Relatorios();
      }
    } catch (e) {
      console.error(e)
    }
  })()