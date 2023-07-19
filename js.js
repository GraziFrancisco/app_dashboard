$(document).ready(() =>{

    $('#documentacao').on('click', () =>{
       // $('#pagina').load('documentacao.html')
       $.get('documentacao.html', data => { //pode-se usar $.get ou $.post
            $('#pagina').html(data)

       })
    })

    $('#suporte').on('click', () =>{
        //$('#pagina').load('suporte.html')
        $.get('suporte.html', data => { //pode-se usar $.get ou $.post
            $('#pagina').html(data)

       })
    })

    $('#competencia').on('change', e => {
        
        let competencia = $(e.target).val()
        //console.log(competencia)

        $.ajax({
            type: 'GET',
            url: 'app.php',
            data: `competencia=${competencia}`, //x-www-form-urlencoded
            dataType: 'json', 
            success: dados => { 
                $('#numeroVendas').html(dados.numeroVendas)
                $('#totalVendas').html(dados.totalVendas)
            },    
            error: erro => {console.log (erro)}
            //METODO, URL, DADOS, SUCESSO, ERRO
        })

    

    })


})