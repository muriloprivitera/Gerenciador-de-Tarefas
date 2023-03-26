import NavBar from './NavBar.js';

class Home{
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
      document.addEventListener('DOMContentLoaded', () => document.getElementById('home').classList.add('active'))
    }
}
(() => {
    try {
      if (!window.Home) {
        window.Home = new Home();
      }
    } catch (e) {
      console.error(e)
    }
  })()