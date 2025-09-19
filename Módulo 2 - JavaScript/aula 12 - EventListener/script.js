let caixa = document.getElementById("mouse")



function EntraDaMouse() {
    caixa.style.backgroundColor = "red";
}

function SaidaMouse() {
    caixa.style.backgroundColor = "yellow";
}

function mudaHTML() {
    let nome = document.querySelector("#nome").value
    caixa.innerHTML = nome
}