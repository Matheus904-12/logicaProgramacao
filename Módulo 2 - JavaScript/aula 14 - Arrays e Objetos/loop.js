for (let i = 0/*inicio*/; i <= 10 /*fim*/; i++/*incremento (salto)*/) {
    console.log(i);
}


let listaProdutos = ["Computador", "Notebook", "Teclado", "Mouse", "Fone"]

//Percorrer Array - For
for (let i = 0; i < listaProdutos.lenght; i++) {
    console.log(listaProdutos[i]);
}

//Percorrer Array - forEach
listaProdutos.forEach((produto)) => {
    console.log(produto);
}