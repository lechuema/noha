document.getElementById('Pedido_mailCliente').addEventListener('change', verificarMail);
function verificarMail() {
   mail=document.getElementById('Pedido_mailCliente').value;
   $.ajax({
      data: { 'mail' :mail  },
      url: 'verficarMail',
      type: 'post',
      success: function (data) {
         if(data['resultado']=='EXISTE')
         {
            alert('El mail ingresado ya está asignado a otro cliente');
            document.getElementById('Pedido_mailCliente').value='';
         }

      },
      error: function(){
         console.log('error');

      }
   })
}


document.getElementById('Pedido_direccionCliente').addEventListener('change', actualizarDireccionEntrega);
function actualizarDireccionEntrega() {
   document.getElementById('Pedido_direccionEntrega').value=document.getElementById('Pedido_direccionCliente').value;
}

/*document.getElementById('Pedido_productos').addEventListener('change', updateValue);
function updateValue() {
   precioPedido=0;
   comboProductos=document.getElementById('Pedido_productos-ts-control');
   for (let i = 0; i < comboProductos.childNodes.length; i++) {
      var arrayDeCadenas = comboProductos.childNodes[i].textContent.split('|');
      precioPedido=precioPedido+parseInt(arrayDeCadenas[1].substr(0,arrayDeCadenas[1].length-1));
   }
   editPrecioTotal=document.getElementById('Pedido_precioTotal');
   editPrecioTotal.value="";
   editPrecioTotal.value=precioPedido;
}*/


document.getElementById('Pedido_cliente_autocomplete').addEventListener('change', buscarCliente);
function buscarCliente(){
   comboCliente=document.getElementById('Pedido_cliente_autocomplete');
   var selectedOption = this.options[comboCliente.selectedIndex];
   var parametros = {'id' : selectedOption.value};
   $.ajax({
      data: { 'id' :selectedOption.value  },
      url: 'buscarCliente',
      type: 'post',
      success: function (data) {
         document.getElementById('Pedido_telefonoCliente').value=data['telefonoCliente'];
         document.getElementById('Pedido_nombreCliente').value=data['nombreCliente'];
         document.getElementById('Pedido_apellidoCliente').value=data['apellidoCliente'];
         document.getElementById('Pedido_mailCliente').value=data['mailCliente'];
         document.getElementById('Pedido_direccionCliente').value=data['direccionCliente'];
         document.getElementById('Pedido_direccionEntrega').value=data['direccionCliente'];
         },
      error: function(){
         document.getElementById('Pedido_telefonoCliente').value='';
         document.getElementById('Pedido_nombreCliente').value='';
         document.getElementById('Pedido_apellidoCliente').value='';
         document.getElementById('Pedido_mailCliente').value='';
         document.getElementById('Pedido_direccionCliente').value='';
         document.getElementById('Pedido_direccionEntrega').value='';
      }
   })
}
