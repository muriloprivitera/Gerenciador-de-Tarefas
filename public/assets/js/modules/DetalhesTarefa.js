import NavBar from './NavBar.js';
import { funcoesCookie } from '../utils.js';

class DetalhesTarefa{
    constructor(){
        this.SideBar = new NavBar(document);
        this.linkApi = `http://localhost/cadastroTarefas/api/api.php`;
        this.linkAtual = window.location.origin;
        this.dragged = null;
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
        document.addEventListener('DOMContentLoaded', () => this.abreDetalhesTarefa())
        document.addEventListener('DOMContentLoaded', () => this.buscaTodasSubTarefas())
        document.addEventListener('DOMContentLoaded', () => this.acionaDropTarefaBD())
        document.getElementById('icon-adicionar-tarefa').addEventListener('click',()=> this.adicionarSubTarefa())
        document.getElementsByClassName('btn-close')[0].addEventListener('click',()=> this.fecharModal())
        document.getElementsByClassName('btn-danger')[0].addEventListener('click',()=> this.fecharModal())
        document.getElementsByClassName('btn-success')[0].addEventListener('click',(event)=> this.enviaAtualizaDadosSubTarefa())
        document.addEventListener('dragover',(event)=> event.preventDefault())
        document.getElementById('drop-progresso').addEventListener('drop',(event)=> this.dropzone(event))
        document.getElementById('drop-finalizado').addEventListener('drop',(event)=> this.dropzone(event))
        document.getElementById('drop-obs').addEventListener('drop',(event)=> this.dropzone(event))
        document.getElementById('sub-tarefas').addEventListener('drop',(event)=> this.dropzone(event))
    }

    acionaDropTarefaBD(){
        setTimeout(() => {
            const elementos = document.querySelectorAll('.sub-tarefa-item');
            Array.from(elementos).forEach((element)=>{
                element.addEventListener('dragstart',(event)=> this.arrastarTarefa(event))
                element.addEventListener('dragend',(event)=> this.arrastarFinalTarefa(event))
            });
        }, 600);
    }

    async enviaAtualizaDadosSubTarefa(){
        const descricao = document.getElementById('descricao-modal').value;
        const idSubTarefa = document.getElementById('descricao-modal').getAttribute('data-id-subTarefa');
        const response = await fetch(`${this.linkApi}/tarefas/atualizaSubTarefa/${idSubTarefa}?descricao=${descricao}`,{
            method: 'PUT',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
                "Authorization":`Bearer ${funcoesCookie.pegarCookie('token')}`
            }
        });
        const {status,mensagem} = await response.json();
        funcoesCookie.validaCookie(status);
        this.fecharModal()
        window.location.reload();
    }

    async abreDetalhesTarefa(){
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        const response = await fetch(`${this.linkApi}/tarefas/abreDetalhesTarefa?id=${id}`,{
            method: 'GET',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
                "Authorization":`Bearer ${funcoesCookie.pegarCookie('token')}`
            }
        });
        const {status,detalhes} = await response.json();
        funcoesCookie.validaCookie(status);
        document.getElementById('load').classList.remove('spinner-border');
        this.preencheInfoTarefa(detalhes)
    }

    preencheInfoTarefa(tarefa){
        document.getElementById('titulo-tarefa').textContent = tarefa.nome_tarefa
        document.getElementById('descricao-tarefa').textContent = tarefa.descricao_tarefa
        document.getElementById('categorias').textContent += tarefa.categorias??'' 
        document.getElementById('atualizacao').textContent += tarefa.atualizado_em??''
    }

    async enviaSubTarefa(event){
        if (event.key != "Enter")return;
        event.preventDefault();
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        const response = await fetch(`${this.linkApi}/tarefas/insereSubTarefa`,{
            method: 'POST',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
                "Authorization":`Bearer ${funcoesCookie.pegarCookie('token')}`
            },
            body:JSON.stringify({
                'titulo':event.target.value,
                'idTarefa':id,
            })
        });
        const {status,mensagem} = await response.json();
        funcoesCookie.validaCookie(status);
        window.location.reload();
    }

    async buscaTodasSubTarefas(){
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        const response = await fetch(`${this.linkApi}/tarefas/selecionaTodasSubTarefas?idTarefa=${id}`,{
            method: 'GET',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
                "Authorization":`Bearer ${funcoesCookie.pegarCookie('token')}`
            }
        });
        const {status,mensagem,tarefas} = await response.json();
        funcoesCookie.validaCookie(status);
        const arrayLen = this.preencheDivSubTarefaBd(tarefas);
        for (let index = 0; index < arrayLen.length; index++) {
            this.controleAppendTarefas(arrayLen[index]);
        }
    }

    fecharModal(){
        document.getElementsByClassName('modal')[0].style.display ="none";
        console.log()
    }

    controleAppendTarefas(tarefas){
        const statusSubTarefa = tarefas.getAttribute('data-status-tarefa');
        if(statusSubTarefa == 'N'){
            document.getElementById('sub-tarefas').append(tarefas);
        }else if(statusSubTarefa == 'P'){
            document.getElementById('drop-progresso').append(tarefas);
        }else if(statusSubTarefa == 'F'){
            document.getElementById('drop-finalizado').append(tarefas);
        }else if(statusSubTarefa == 'O'){
            document.getElementById('drop-obs').append(tarefas);
        }
    }

    adicionarSubTarefa(){
        document.getElementById('sub-tarefas').append(this.inserirDivSubTarefa());
        const elemento = document.getElementsByClassName('sub-tarefa-item');
        for (const divs of elemento) {
            divs.addEventListener('dragstart',(event)=> this.arrastarTarefa(event));
            divs.addEventListener('dragend',(event)=> this.arrastarFinalTarefa(event));
        }
        return;
    }

    async atualizaStatusSubTarefa(event){
        const card = event.target.parentNode.parentNode.parentNode;
        const idSubTarefa = event.target.getAttribute('id');
        const statusColuna = card.getAttribute('data-status');
        const response = await fetch(`${this.linkApi}/tarefas/atualizaStatusSubTarefa/${idSubTarefa}?status=${statusColuna}`,{
            method: 'PUT',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
                "Authorization":`Bearer ${funcoesCookie.pegarCookie('token')}`
            }
        });
        const {status} = await response.json();
        funcoesCookie.validaCookie(status);
    }

    preencheDivSubTarefaBd(subTarefas){
        let html =``;
        for (const tarefa of subTarefas) {
            html += 
            `
            <div class="d-flex sub-tarefa-item pointer" id="${tarefa.id}" onclick="DetalhesTarefa.clickInformacoesTarefas(this)" data-status-tarefa="${tarefa.status_sub_tarefa}" style="margin:5px;margin-top: 15px;" draggable="true">
                <div style="margin:8px;height:40px;" class="form-control">${tarefa.titulo}</div>
            </div>
            `;
        }
        const node = new DOMParser().parseFromString(html,'text/html');
        return node.body.childNodes;
    }

    async clickInformacoesTarefas(elemento){
        const idSubTarefa = elemento.getAttribute('id');
        const response = await fetch(`${this.linkApi}/tarefas/pegaInfoUmaSubTarefa/${idSubTarefa}`,{
            method: 'GET',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
                "Authorization":`Bearer ${funcoesCookie.pegarCookie('token')}`
            }
        });
        const {status,mensagem} = await response.json();
        funcoesCookie.validaCookie(status);
        this.preencheModal(mensagem);
    }

    preencheModal(subTarefas){
        document.getElementById('titulo-modal').innerText = subTarefas.titulo;
        document.getElementById('descricao-div').innerHTML = `<textarea id="descricao-modal" placeholder="Descri\u00e7\u00e3o da tarefa" data-id-subTarefa="${subTarefas.id}">${subTarefas.descricao}</textarea>`;
        document.getElementsByClassName('modal')[0].style.display = 'block';
    }

    inserirDivSubTarefa(){
        const html = 
        `
            <div class="d-flex sub-tarefa-item" style="margin:5px;margin-top: 15px;" draggable="true">
                <input style="margin:8px;height:40px;" onkeypress="DetalhesTarefa.enviaSubTarefa(event)" class="form-control" >
            </div>
        `;
        const node = new DOMParser().parseFromString(html,'text/html');
        return node.body.firstChild;
    }

    arrastarTarefa(event){
        console.log("dragStart");
        event.dataTransfer.clearData();
        event.dataTransfer.setData('text/pain',event.target.id);
        this.dragged = event.target;
    }
    arrastarFinalTarefa(event){
        console.log("dragEnd");
        this.atualizaStatusSubTarefa(event)
    }
    overDrop(event){
        console.log('over')
        event.preventDefault()
    }
    dropzone(event){
        console.log("Drop");
        event.preventDefault();
        if (event.target.className === "sub-tarefas") {
            this.dragged.remove();
            event.target.append(this.dragged);
        }
    }

}
(() => {
    try {
        if (!window.DetalhesTarefa) {
            window.DetalhesTarefa = new DetalhesTarefa();
        }
    } catch (e) {
        console.error(e)
    }
})()