let formaPagamento = "pix"
let valorGasto = 1000

switch (formaPagamento) {
    case "pix":
        console.log(valorGasto * 0.9);
        break

    case "debito":
        console.log(valorGasto * 0.95);
        break

    case "credito":
        console.log(valorGasto);
        break
}