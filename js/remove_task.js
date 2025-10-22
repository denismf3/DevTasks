const btnRemove = document.getElementById('btnRemove');
const btnConfirmRemove = document.getElementById('btnConfirmRemove');
const checkboxes = document.querySelectorAll('.checkbox-remover');
const tarefas = document.querySelectorAll('.post-it');

let modoExclusao = false;
btnConfirmRemove.style.display = 'none';

btnRemove.addEventListener('click', () => {
    modoExclusao = !modoExclusao;
    checkboxes.forEach(cb => {
        cb.style.display = modoExclusao ? 'inline-block' : 'none';
        cb.checked = false;
    });
    btnConfirmRemove.style.display = modoExclusao ? 'inline-block' : 'none';
    btnRemove.textContent = modoExclusao ? 'Cancelar' : '🗑️ Remover';
});

btnConfirmRemove.addEventListener('click', () => {
    const idsSelecionados = [];
    tarefas.forEach(tarefa => {
        const checkbox = tarefa.querySelector('.checkbox-remover');
        if (checkbox && checkbox.checked) {
            idsSelecionados.push(tarefa.getAttribute('data-id'));
        }
    });

    if (idsSelecionados.length === 0) return;

    fetch('../pages/remove_task.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ids: idsSelecionados })
    })
        .then(async res => {
            const text = await res.text();
            try {
                return JSON.parse(text);
            } catch {
                throw new Error("Resposta não é JSON: " + text);
            }
        })
        .then(data => {
            if (data.status === 'success') {
                location.reload(); // recarrega a página após exclusão
            }
        })
        .catch(err => console.error('Erro na requisição: ', err));
});
