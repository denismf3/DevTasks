document.addEventListener('DOMContentLoaded', () => {
    const btnConclude = document.getElementById('btnConclude');
    const btnConfirmConclude = document.getElementById('btnConfirmConclude');
    const checkboxesConclude = document.querySelectorAll('.checkbox-conclude');
    const tarefas = document.querySelectorAll('.post-it');

    let modoConclusao = false;
    btnConfirmConclude.style.display = 'none';


    btnConclude.addEventListener('click', () => {
        modoConclusao = !modoConclusao;

        checkboxesConclude.forEach(cb => {
            cb.style.display = modoConclusao ? 'inline-block' : 'none';
            cb.checked = false;
        });

        btnConfirmConclude.style.display = modoConclusao ? 'inline-block' : 'none';
        btnConclude.textContent = modoConclusao ? 'Cancelar' : '✅ Concluir';
    });


    btnConfirmConclude.addEventListener('click', async () => {
        const idsSelecionados = [];
        tarefas.forEach(tarefa => {
            const checkbox = tarefa.querySelector('.checkbox-conclude');
            if (checkbox && checkbox.checked) {
                idsSelecionados.push(tarefa.getAttribute('data-id'));
            }
        });

        if (idsSelecionados.length === 0) return;

        try {
            const response = await fetch('conclude_task.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: idsSelecionados })
            });

            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch {
                throw new Error("Resposta não é JSON: " + text);
            }

            if (data.status === 'success') {

                tarefas.forEach(tarefa => {
                    if (idsSelecionados.includes(tarefa.getAttribute('data-id'))) {
                        tarefa.remove();
                    }
                });

                modoConclusao = false;
                btnConfirmConclude.style.display = 'none';
                btnConclude.textContent = '✅ Concluir';
                checkboxesConclude.forEach(cb => cb.style.display = 'none');
            } else {
                console.error('Erro ao concluir tarefas: ', data.message);
            }
        } catch (error) {
            console.error('Erro de comunicação com o servidor: ', error);
        }
    });
});
