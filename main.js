//cabeçario que indentifica as requisições ajax;
xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

//por enquanto esta fazendo o edit e delete do js;
function OpenEdit(controller,id)
{
   window.location.href = controller+'/'+id; 
}
function jsDelete(controller, id)
{
    const form =  document.getElementById('delete-form-'+id);
    form.style.display ='flex';
}

function toggleMenu()
{   
    menu = $('#main-menu');
    menu.animate({
        width: "toggle"
    });
}