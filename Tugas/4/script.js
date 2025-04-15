document.getElementById('submitBtn').addEventListener('click', function() {
    const name = document.getElementById('name').value;
    const numChoices = parseInt(document.getElementById('numChoices').value);

    if (!name || isNaN(numChoices) || numChoices < 1) {
        alert('Harap masukkan nama dan jumlah pilihan yang valid.');
        return;
    }

    let choiceInputs = '<h3>Masukkan Pilihan:</h3>';
    for (let i = 1; i <= numChoices; i++) {
        choiceInputs += `<div class="choice-container">
                            <label for="choice${i}">Pilihan ${i}:</label>
                            <input type="text" id="choice${i}" placeholder="Teks Pilihan ${i}">
                        </div>`;
    }
    choiceInputs += '<br><button type="button" id="submitChoicesBtn">Submit Pilihan</button>';
    document.getElementById('choiceInputs').innerHTML = choiceInputs;
    document.getElementById('choiceInputs').classList.remove('hidden');

    document.getElementById('submitChoicesBtn').addEventListener('click', function() {
        let choices = [];
        for (let i = 1; i <= numChoices; i++) {
            const choice = document.getElementById(`choice${i}`).value;
            if (!choice) {
                alert('Harap masukkan semua pilihan.');
                return;
            }
            choices.push(choice);
        }

        let finalHTML = '<h3>Pilihan:</h3>';
        choices.forEach((choice, index) => {
            finalHTML += `<input type="radio" name="finalChoice" id="finalChoice${index}" value="${choice}">
                          <label for="finalChoice${index}">${choice}</label><br>`;
        });
        finalHTML += '<br><button type="button" id="showResultBtn">Tampilkan Hasil</button>';
        document.getElementById('finalChoice').innerHTML = finalHTML;
        document.getElementById('finalChoice').classList.remove('hidden');

        document.getElementById('showResultBtn').addEventListener('click', function() {
            const selectedChoice = document.querySelector('input[name="finalChoice"]:checked');
            if (!selectedChoice) {
                alert('Harap pilih salah satu pilihan.');
                return;
            }
            const result = `Hallo, nama saya ${name}, saya mempunyai sejumlah ${numChoices} pilihan yaitu ${choices.join(', ')}, dan saya memilih ${selectedChoice.value}.`;
            alert(result);
        });
    });
});
