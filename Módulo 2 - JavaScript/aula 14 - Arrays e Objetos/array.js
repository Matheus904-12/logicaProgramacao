//Array
let listaTimes = ["São Paulo", "Santos", "Flamengo"]

//Acessar itens em um array
console.log(listaTimes[0]);
console.log(listaTimes[1]);
console.log(listaTimes[2]);

//Adiconar itens em um Array
listaTimes.push("Bota Fogo")
listaTimes.push("Flamengo")
console.log(listaTimes);

//Verificar o tamanho da lista
console.log(listaTimes.length);

//Adicionar um item na primeira posição
listaTimes.unshift("Fluminense")
listaTimes.unshift("Corinthians")
console.log(listaTimes);

//Remover um item no inicio do array
listaTimes.shift()
console.log(listaTimes);

//Remover o último elemento dp array
listaTimes.pop()
console.log(listaTimes);

//Encontrar a posição de um elemento
console.log(listaTimes.indexOf("Santos"));

///////////////////////////////////////////////////////////

let listaNomes = ["Marcos", "Diego", "Camila", "Matheus"]

//Remover elementos de um Array
listaNomes.splice(0,1) //Exemplo = (posição, quantidde de elementos)
console.log(listaNomes);

//Alterando um valor naquela posição
listaNomes.splice(1,1, "Robson") //Se eu adicionar mais valores ele vai adicionar mais elementos seguindo a posição seguindo a posição
console.log(listaNomes);

//Adicionar itens
listaNomes.splice(2,0, "Gabriel", "Camila")
console.log(listaNomes);

///////////////////////////////////////////////////////////