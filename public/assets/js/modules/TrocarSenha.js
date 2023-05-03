import {funcoesCookie,validaRequisicao} from '../utils.js';
class TrocarSenha{
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
        document.getElementById('trocar').addEventListener('click',()=> this.alteraSenha())
    }

    async alteraSenha(){
        const senha = document.getElementById('senha').value;
        const response = await fetch(`${this.linkApi}/usuarios/usuarioEsqueceuSenha?senha=${senha}`,{
            method: 'PUT',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
                "Authorization":`Bearer ${funcoesCookie.pegarCookie('tokenEmail')}`
            }
        });
        const {status,mensagem} = await response.json();
        funcoesCookie.validaCookie(status);
        validaRequisicao.validaRequisicao(status,'Senha Alterada',mensagem,'../../src/views/login.html');
    }
}
(() => {
    try {
      if (!window.TrocarSenha) {
        window.TrocarSenha = new TrocarSenha();
      }
    } catch (e) {
      console.error(e)
    }
  })()