import {modalMensagem} from '../utils.js';
class Usuario {
    constructor(){
        this.init()
        this.linkApi = `http://localhost/cadastroTarefas/api/api.php`;
        this.jwt = '';
    }

    async init(){
        await this.loadView();
    }

    async loadView(){
        this.divs = {
            elementoClickCadastro:document.getElementById('cadastrar'),
            elementoClickLogin:document.getElementById('entrar'),
        }
        await this.listeners()
    }

    async listeners() {
        this.divs.elementoClickCadastro?.addEventListener('click', () => this.cadastroUsuario())
        this.divs.elementoClickLogin?.addEventListener('click', () => this.loginUsuario())
    }

    async cadastroUsuario(){
        const response = await fetch(`${this.linkApi}/usuarios/insereusuario`,{
            method: 'POST',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json"
            },
            body:JSON.stringify({
                'nome':document.getElementById('nome').value,
                'email':document.getElementById('email').value,
                'senha':document.getElementById('senha').value
            })

        });
        const {status,mensagem} = await response.json();
        const mensagemErro = modalMensagem.mensagemErro(mensagem);
        const mensagemSucesso = modalMensagem.mensagemSucesso('Usuario Inserido',mensagem,'../../src/views/login.html','Fechar');

        if(status !== 'OK'){
            document.getElementById('body').append(mensagemErro);
            document.getElementsByClassName('modal')[0].style.display = 'block';
            return;
        }
        document.getElementById('body').append(mensagemSucesso);
        document.getElementsByClassName('modal')[0].style.display = 'block';
    }

    async loginUsuario(){
        const response = await fetch(`${this.linkApi}/usuarios/login`,{
            method: 'POST',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json"
            },
            body:JSON.stringify({
                'email':document.getElementById('email').value,
                'senha':document.getElementById('senha').value
            })

            
        });
        const {status,mensagem,token} = await response.json();
        const mensagemErro = modalMensagem.mensagemErro(mensagem);
        const mensagemSucesso = modalMensagem.mensagemSucesso('Login aceito',mensagem,'../../src/views/home.html','Ok');
        if(status !== 'OK'){
            document.getElementById('body').append(mensagemErro);
            document.getElementsByClassName('modal')[0].style.display = 'block';
            return;
        }
        this.jwt = token;
        document.getElementById('body').append(mensagemSucesso);
        document.getElementsByClassName('modal')[0].style.display = 'block';
        document.cookie=`token=${token}`;
    }

}

(() => {
    try {
      if (!window.Usuario) {
        window.Usuario = new Usuario();
      }
    } catch (e) {
      console.error(e)
    }
  })()