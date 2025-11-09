document.querySelector('#contact-form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const inputs = this.querySelectorAll('input, textarea');
  let valid = true;

  inputs.forEach(input => {
    if (!input.value.trim()) {
      valid = false;
    }
  });

  if (valid) {
    alert('⚡ Gracias por contactarnos. Te responderemos pronto.');
    this.reset();
  } else {
    alert('Por favor rellena todos los campos antes de enviar.');
  }
});

const botonesAgregar = document.querySelectorAll('.btn-electric');


botonesAgregar.forEach(boton => {
  boton.addEventListener('click', () => {
    alert('✅ Producto agregado al carrito.');
  });
});

