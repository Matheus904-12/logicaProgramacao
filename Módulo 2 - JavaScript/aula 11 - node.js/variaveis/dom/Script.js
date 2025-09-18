/*window.document.body.style.backgroundColor = "black"
document.body.style.color = "white"

window.alert("Seja Bem-vindo")

window.prompt("Login")
window.prompt("Senha")

window.confirm("Site com Virus, Deseja continuar ?")*/

//get.Element/sBy

let paragrafo1 = document.getElementsByTagName("p")[0] //Posição
paragrafo1.innerHTML = "Você foi Pego na TAG"
paragrafo1.style.backgroundColor = "red"

let paragrafo2 = document.getElementById("paragrafo2")
paragrafo2.innerHTML = "Você foi pego no ID"
paragrafo2.style.backgroundColor = "green"

let paragrafo3 = document.getElementsByClassName("paragrafo3")[0] //Posição
paragrafo3.innerHTML = "Você foi pego na CLASS"
paragrafo3.style.backgroundColor = "blue"


//querySelector

let paragrafo4 = document.querySelector("#paragrafo4")
paragrafo4.innerHTML = "Você foi pego no ID QS"
paragrafo4.style.backgroundColor = "violet"

let paragrafo5 = document.querySelectorAll("p")[4] // ou .paragrafo5 [0]
paragrafo5.innerHTML = "Você foi pego no ID QS All"
paragrafo5.style.backgroundColor = "white"
