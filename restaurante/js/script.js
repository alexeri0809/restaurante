document.getElementById("formPedido").addEventListener("submit", async function(e) {
    e.preventDefault();
    const form = new FormData(this);
    const res = await fetch("pedido.php", {
      method: "POST",
      body: form
    });
    const msg = await res.text();
    alert(msg);
  });
  