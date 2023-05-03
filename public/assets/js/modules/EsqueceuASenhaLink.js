import {validaRequisicao} from '../utils.js';
class EsqueceuASenhaLink{
    constructor(){
        this.init();
        this.linkApi = `http://localhost/cadastroTarefas/api/api.php`;
        this.jwt = '';
    }
    async init(){
        await this.loadView();
    }

    async loadView(){
        await this.listeners()
    }

    async listeners() {
        document.getElementById('enviar').addEventListener('click',()=> this.validaEnviaEmail())
    }

    async validaEnviaEmail(){
        const response = await fetch(`${this.linkApi}/usuarios/validaEnviaEmail`,{
            method: 'POST',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json"
            },
            body:JSON.stringify({
                'email':document.getElementById('email').value,
            })
        });
        const {status,mensagem,token} = await response.json();
        this.jwt = token;
        document.cookie=`tokenEmail=${token}`;
        validaRequisicao.validaRequisicao(status,'Email enviado',mensagem,'../../src/views/login.html');
    }
}
(() => {
    try {
      if (!window.EsqueceuASenhaLink) {
        window.EsqueceuASenhaLink = new EsqueceuASenhaLink();
      }
    } catch (e) {
      console.error(e)
    }
  })()