function diaSemana() {
    var x = document.getElementById("fecha");
    let date = new Date(x.value.replace(/-+/g, '/'));

    let options = {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    };
    alert("Hello world!");
    console.log(date.toLocaleDateString('es-MX', options));

  }
