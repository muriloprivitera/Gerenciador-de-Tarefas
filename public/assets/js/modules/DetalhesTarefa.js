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
        document.getElementById('icon-adicionar-tarefa').addEventListener('click',()=> this.adicionarSubTarefa())
        document.addEventListener('dragover',(event)=> event.preventDefault())
        document.getElementById('drop-progresso').addEventListener('drop',(event)=> this.dropzone(event))
        document.getElementById('drop-finalizado').addEventListener('drop',(event)=> this.dropzone(event))
        document.getElementById('drop-obs').addEventListener('drop',(event)=> this.dropzone(event))
        document.getElementById('sub-tarefas').addEventListener('drop',(event)=> this.dropzone(event))
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
        const {status,total,detalhes} = await response.json();
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
            document.getElementById('sub-tarefas').append(arrayLen[index])
            
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

    preencheDivSubTarefaBd(subTarefas){
        let html =``;
        for (const tarefa of subTarefas) {
            html += 
            `
            <div class="d-flex sub-tarefa-item" style="margin:5px;margin-top: 15px;" draggable="true">
                <input style="margin:8px;height:40px;" class="form-control" value=${tarefa.titulo}>
            </div>
            `;
        }
        const node = new DOMParser().parseFromString(html,'text/html');
        return node.body.childNodes;
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