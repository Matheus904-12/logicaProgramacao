//Objeto
let produto = {
    nome:"Dell Inspiron 15 3511",
    fabricante:"Dell",
    preco: 3900,
    processador: "Core I5 - 1135G7",
    memoriaRam: 8,
    memoriaInterna: 256,
    isActive: "on"
}

//Acessanndo um item no objeto
console.log(produto.nome); //método mais comum
console.log(produto["nome"]); //chave dinâmica e com caracteres especiais

//Adicionar um item no objeto
produto.isActive = "off"
produto["cor"] = "preto"
console.log(produto);

//Remover um item
delete produto.preco
console.log(produto);
