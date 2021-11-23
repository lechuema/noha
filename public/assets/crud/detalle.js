function prueba(e) {
    //panel = document.getElementById('content-3');
    console.log(e);
    elemento=document.getElementById('Pedido_detallePedido_1_producto_id');
    alert(elemento.value);

        panel.addEventListener('change', actualizarPrecio);

        function actualizarPrecio() {
            for (let node of panel.childNodes) {
                alert(node); // enseña todos los nodos de la colección
            }
        }


}