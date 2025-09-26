let listaProdutos = [
    {
        nome: "arroz",
        preco: 15,
        validade: 1125
    },

    {
        nome: "feijão",
        preco: 12,
        validade: 1025
    },

    {
        nome: "carne",
        preco: 5,
        validade: 1025
    },

    {
        nome: "alface",
        preco: 12,
        validade: "undefined"
    },

    {
        nome: "refrigerante",
        preco: 8,
        validade: 1212
    },
]

console.log(listaProdutos[0]);

//Percorrer Array - forEach
listaProdutos.forEach((produto) => {
    console.log(`O ${produto.nome} está custando R$${produto.preco} e está válido até ${produto.validade}`);
});

let listaProdutosCaros = listaProdutos.filter(produto => produto.preco > 10)
console.log(listaProdutosCaros);