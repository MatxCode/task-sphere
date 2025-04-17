// assets/controllers/project_board_controller.js
import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    connect() {
        console.log('Stimulus connecté ✅');

        document.querySelectorAll('.issue-container').forEach(issueContainer => {
            issueContainer.ondrop = (e) => {
                e.preventDefault();

                const id = e.dataTransfer.getData('text/plain');
                const status = e.target.dataset['status'];

                fetch(`/issue/${id}/update_status_ajax`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `status=${status}`
                }).then(response => {
                    if (!response.ok) {
                        console.error('Erreur lors de la mise à jour du statut');
                    }
                });

                e.target.appendChild(document.getElementById(id));
            };

            issueContainer.ondragover = (e) => e.preventDefault();
        });

        document.querySelectorAll('.issue-item').forEach(issueItem => {
            issueItem.addEventListener('dragstart', (e) => {
                e.dataTransfer.dropEffect = 'move';
                e.dataTransfer.setData('text/plain', e.target.id);
            });
        });
    }
}
