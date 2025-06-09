document.getElementById("formPedido").addEventListener("submit", async function(e) {
  e.preventDefault();

  const form = this;
  const botao = form.querySelector('button[type="submit"]');
  const mensagem = document.getElementById("msgPedido");

  // Limpa mensagem anterior
  if(mensagem) mensagem.textContent = "";

  // Desabilita botão e muda texto
  botao.disabled = true;
  botao.textContent = "Enviando...";

  try {
    const formData = new FormData(form);
    const resposta = await fetch("pedido.php", {
      method: "POST",
      body: formData
    });

    if (!resposta.ok) throw new Error("Erro na rede");

    const texto = await resposta.text();

    if (mensagem) {
      mensagem.textContent = texto;
      mensagem.className = "mt-4 p-2 rounded text-white";

      if (texto.toLowerCase().includes("sucesso")) {
        mensagem.classList.add("bg-green-600");
        // Opcional: resetar o form só se o pedido deu certo
        form.reset();
      } else {
        mensagem.classList.add("bg-red-600");
      }
    } else {
      alert(texto);
    }

  } catch (error) {
    if (mensagem) {
      mensagem.textContent = "Erro ao enviar pedido. Tente novamente.";
      mensagem.className = "mt-4 p-2 rounded bg-red-600 text-white";
    } else {
      alert("Erro ao enviar pedido. Tente novamente.");
    }
  }

  // Reabilita botão e restaura texto
  botao.disabled = false;
  botao.textContent = "Fazer Pedido";
});
