//cabeçario que indentifica as requisições ajax;
var xhr = new XMLHttpRequest();
xhr.open('GET' , 'localhost:9999', true);
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

function toggleTasks() {
    menu = $('#notification-tab');

    if (menu.is(':visible')) {
        // If menu is visible, animate it to move out to the left
        menu.animate({
            width: "toggle",
            marginLeft: "200px", // Adjust the value based on your layout
            opacity: 0
        }, function () {
            menu.hide();
        });
    } else {
        // If menu is hidden, animate it to move in from the left
        menu.show().css({
            marginLeft: "0", // Set initial position outside the view
            opacity: 0
        }).animate({
            marginLeft: 0,
            opacity: 1
        }, 400); // Adjust the duration as needed
    }
}