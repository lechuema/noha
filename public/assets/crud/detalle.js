function prueba() {
    panel = document.getElementById('content-3');
    console.log(panel);
    /*elemento=document.getElementById('Pedido_detallePedido_1_producto_id');
    alert(elemento.value);

        panel.addEventListener('change', actualizarPrecio);

        function actualizarPrecio() {
            for (let node of panel.childNodes) {
                alert(node); // enseña todos los nodos de la colección
            }
        }*/

    if (panel.hasChildNodes()) {
        var children = panel.childNodes;

        for (var i = 0; i < children.length; i++) {
            // do something with each child as children[i]
            console.log(children[i]);
            // NOTE: List is live, adding or removing children will change the list
        }
    }


}