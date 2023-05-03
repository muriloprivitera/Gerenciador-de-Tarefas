export  const modalMensagem ={
    mensagemSucesso:(titulo,texto,redirect,tituloBotao)=>{
        let html = 
            `
                <div class="modal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${titulo}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>${texto}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><a href="${redirect}" style="text-decoration: none;color: white !important;" class="text-white-50 fw-bold">${tituloBotao}</a></button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            const node = new DOMParser().parseFromString(html,'text/html');
            return node.body.firstChild;
    },
    mensagemErro:(texto)=>{
        let html = 
            `
                <div class="modal" id="tentar-novamente" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Erro</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>${texto}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button"  onclick="document.querySelector('#tentar-novamente').remove()" class="btn btn-primary">Tentar Novamente</button>
                            </div>
                        </div>
                </div>
            </div>
            `;
            const node = new DOMParser().parseFromString(html,'text/html')
            return node.body.firstChild;
    }
}

export const funcoesCookie ={
    validaCookie:(status)=>{
        if(typeof status == 'undefined'|| status != 'OK'){
            var linkAtual = window.location.origin;
            funcoesCookie.deleteCookie('token');
            window.location.href = `${linkAtual}/cadastroTarefas/src/views/login.html`;
        }
    },

    pegarCookie: (name)=>{
        var cookies = document.cookie;
        var prefix = name + "=";
        var begin = cookies.indexOf("; " + prefix);
    
        if (begin == -1) {
            begin = cookies.indexOf(prefix);
            
            if (begin != 0) {
                return null;
            }
    
        } else {
            begin += 2;
        }
    
        var end = cookies.indexOf(";", begin);
        
        if (end == -1) {
            end = cookies.length;                        
        }
    
        return unescape(cookies.substring(begin + prefix.length, end));
    },

    deleteCookie:(name)=> {
        if (funcoesCookie.pegarCookie(name)) {
            document.cookie = name + "=";
        }
    },

    validaCookieUsuario:()=>{
        if(funcoesCookie.pegarCookie('token') == ''||!funcoesCookie.pegarCookie('token'))window.location = `../../src/views/login.html`;
    }
}

export const validaRequisicao ={
    validaRequisicao:(status,titulo,mensagem,caminho)=>{
        const mensagemErro = modalMensagem.mensagemErro(mensagem);
        if(status !== 'OK'){
            document.getElementById('body').append(mensagemErro);
            document.getElementsByClassName('modal')[0].style.display = 'block';
            return;
        }
        const mensagemSucesso = modalMensagem.mensagemSucesso(titulo,mensagem,caminho,'Fechar');
        document.getElementById('body').append(mensagemSucesso);
        document.getElementsByClassName('modal')[0].style.display = 'block';
    },
}
export const sair = {
    sair:()=>{
        document.cookie = 'token' + "=";
        document.cookie = 'tokenEmail' + "=";
        window.location.reload()
    }
}