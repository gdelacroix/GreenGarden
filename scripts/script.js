
document.querySelector('.btn-outline-success').addEventListener('click', () => {
    const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
    cartModal.show();
});


 // Gestion de la fermeture de la modale pour supprimer la classe "show"
 const cartModal = document.getElementById('cartModal');
 cartModal.addEventListener('hidden.bs.modal', function () {
     // Retirer la classe `show` si elle est encore présente
     cartModal.classList.remove('show');
     // Supprimer l'attribut `aria-modal` pour éviter des comportements indésirables
     cartModal.removeAttribute('aria-modal');
 });



 document.getElementById('type_client').addEventListener('change', function () {
    const societeField = document.getElementById('nom_societe');
    societeField.style.display = this.value == '2' ? 'block' : 'none';
});
document.getElementById('meme_adresse').addEventListener('change', function () {
    const isChecked = this.checked;
    const livraisonSelect = document.getElementById('adresse_livraison');
    const nouvelleAdresseLivraison = document.getElementById('nouvelle_adresse_livraison');
    
    livraisonSelect.disabled = isChecked;
    nouvelleAdresseLivraison.style.display = isChecked ? 'none' : 'block';
});

const toggleAddressInputs = (selectId, inputContainerId) => {
    document.getElementById(selectId).addEventListener('change', function () {
        const container = document.getElementById(inputContainerId);
        console.log("test");
        container.style.display = this.value=== 'Nouvelle' ? 'block' : 'none';
    });
};

toggleAddressInputs('adresse_facturation', 'nouvelle_adresse_facturation');
toggleAddressInputs('adresse_livraison', 'nouvelle_adresse_livraison');