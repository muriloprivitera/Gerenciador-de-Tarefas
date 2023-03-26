import NavBar from './NavBar.js';
import {funcoesCookie,validaRequisicao} from '../utils.js';

class Painel{
    constructor(){
        this.SideBar = new NavBar(document);
        this.linkApi = `http://localhost/cadastroTarefas/api/api.php`;
        this.linkAtual = window.location.origin;
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
        document.addEventListener('DOMContentLoaded', () => document.getElementById('painel').classList.add('active'))
        document.addEventListener('DOMContentLoaded', () => this.buscaTarefasUsuario())
        document.getElementById('cadastrar-tarefa').addEventListener('click', () => this.abreInsercaoTarefa())
        document.getElementById('envia-tarefa').addEventListener('click', () => this.cadastraTarefa())
        document.getElementById('excluir-tarefa').addEventListener('click', () => this.excluiTarefa())
    }

    async buscaTarefasUsuario(paginacao=0){
        const response = await fetch(`${this.linkApi}/tarefas/selecionaTodasTarefas?quantidade=20&inicio=${paginacao}`,{
            method: 'GET',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
                "Authorization":`Bearer ${funcoesCookie.pegarCookie('token')}`
            }
        });
        const {status,total,tarefas} = await response.json();
        funcoesCookie.validaCookie(status);
        paginacao = paginacao + 20;
        this.montaTabelaTarefas(tarefas);
        if(total > paginacao){
            this.buscaTarefasUsuario(paginacao);
        }else{
            document.getElementById('load').classList.remove('spinner-border');
        }
        
        
    }

    montaTabelaTarefas(tarefas){
        const tableData = 
        `
            ${tarefas?.map((obj)=>{
                return(`
                    <tr class="tarefa">
                        <th scope="row"><input id="${obj.id}" class="checkbox-exclusao" type="checkbox">${obj.id}</th>
                        <td onclick="Painel.abreDetalhesTarefa(${obj.id})">${obj.nome_tarefa}</td>
                        <td>${obj.status_tarefa}</td>
                        <td>${obj.hora_calculada === null ? 'N\u00e3o possui' :obj.hora_calculada}</td>
                        <td>${obj.criado_em}</td>
                        <td>${obj.atualizado_em === null ? '' :obj.atualizado_em}</td>
                    </tr>
                `)
            }).join('')}
        `;
        const tableBody = document.querySelector('#tbody-painel');
        tableBody.innerHTML+= tableData;
    }

    abreInsercaoTarefa(){
        document.getElementById('inputs-cadastro').classList.toggle('inputs-cadastro');
    }

    async cadastraTarefa(){
        const response = await fetch(`${this.linkApi}/tarefas/insereTarefa`,{
            method: 'POST',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
                "Authorization":`Bearer ${funcoesCookie.pegarCookie('token')}`
            },
            body:JSON.stringify({
                'nomeTarefa':document.getElementById('nome-tarefa').value,
                'descricaoTarefa':document.getElementById('descricao-tarefa').value,
            })
        });
        const {status,mensagem} = await response.json();
        funcoesCookie.validaCookie(status);
        validaRequisicao.validaRequisicao(status,mensagem,'../../src/views/painel.html');
    }

    async excluiTarefa(){
        let checkbox = document.getElementsByClassName('checkbox-exclusao');
        for (const key in checkbox) {
            if(checkbox[key].checked != true)continue;
            const response = await fetch(`${this.linkApi}/tarefas/excluiTarefa?id=${checkbox[key].id}`,{
                method: 'DELETE',
                mode: 'cors',
                headers: {
                    "Content-Type": "application/json",
                    "Authorization":`Bearer ${funcoesCookie.pegarCookie('token')}`
                },
            });
            const {status,mensagem} = await response.json();
            funcoesCookie.validaCookie(status);
            validaRequisicao.validaRequisicao(status,mensagem,'../../src/views/painel.html');
        }
    }

    abreDetalhesTarefa(idTarefa){
        window.location = `../../src/views/detalhesTarefa.html?id=${idTarefa}`;
    }

}
(() => {
    try {
        if (!window.Painel) {
            window.Painel = new Painel();
        }
    } catch (e) {
        console.error(e)
    }
})()