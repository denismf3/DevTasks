document.addEventListener("DOMContentLoaded", () => {
    const botoes = document.querySelectorAll(".btn-delete");

    botoes.forEach(botao => {
        botao.addEventListener("click", async () => {
            const id = botao.dataset.id;

            if (!confirm("Tem certeza que deseja excluir esta tarefa?")) return;

            const formData = new FormData();
            formData.append("id", id);

            try {
                const resposta = await fetch("remove_task.php", {
                    method: "POST",
                    body: formData
                });

                const resultado = await resposta.json();

                if (resultado.status === "success") {
                    const elemento = document.getElementById(`tarefa-${id}`);
                    if (elemento) {
                        elemento.classList.add("fade-out");
                        setTimeout(() => elemento.remove(), 400);
                    }
                    mostrarAlerta("Tarefa removida com sucesso ✅");
                } else {
                    mostrarAlerta("Erro ao remover tarefa ❌: " + (resultado.message || ""), true);
                }
            } catch (erro) {
                mostrarAlerta("Erro de conexão com o servidor ❌", true);
                console.error(erro);
            }
        });
    });
});

function mostrarAlerta(mensagem, erro = false) {
    const alerta = document.createElement("div");
    alerta.className = "alerta" + (erro ? " erro" : "");
    alerta.textContent = mensagem;
    document.body.appendChild(alerta);

    setTimeout(() => alerta.classList.add("mostrar"), 10);

    setTimeout(() => {
        alerta.classList.remove("mostrar");
        setTimeout(() => alerta.remove(), 400);
    }, 3000);
}
